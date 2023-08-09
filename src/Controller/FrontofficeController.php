<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Entity\Auto;
use App\Entity\Client;
use App\Entity\Agence;
use App\Entity\Feedback;
use App\Entity\Location;
use App\Entity\Notification;
use App\Entity\Disponibility;
use App\Entity\Payment;
use App\Entity\Settings;
use App\Entity\Blacklist;
use App\Form\FeedbackType;
use App\Form\LocationType;
use App\Form\UserType;
use App\Form\ClientType;
use App\Repository\AgenceRepository;
use App\Repository\VehiculeRepository;
use App\Repository\AutoRepository;
use App\Repository\FeedbackRepository;
use App\Repository\LocationRepository;
use App\Repository\ClientRepository;
use App\Repository\NotificationRepository;
use App\Repository\SettingsRepository;
use App\Repository\PaymentRepository;
use App\Repository\PromoRepository;
use App\Repository\BlacklistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use \DateTime;


#[Route('/front')]
class FrontofficeController extends AbstractController
{
    private $passwordHasher;
    private $usr_back ;
    private $client;


    public function __construct(UserPasswordHasherInterface $passwordHasher,HttpClientInterface $client)
    {
        $this->passwordHasher = $passwordHasher;
        $this->client = $client;
    }   
    public function getBack()
    {
        return $this->usr_back;
    }
    /**
     * @Route("/", name="home_index")
     */
    public function index(AgenceRepository $agenceRepository, VehiculeRepository $vehiculeRepository, SettingsRepository $settingsRepo): Response
    {
        if($this->getUser() != null) {
            if (isset($this->getUser()->getRoles()['ROLE_MODERATOR']) || isset($this->getUser()->getRoles()['ROLE_ADMIN'])) {
                return $this->redirectToRoute('dashboard_index');
            }
        }
        $today = date("Y-m-d");
        $today = strtotime($today." +1 day");
        $today = date("Y-m-d",$today);

        $next = date("Y-m-d");
        $next = strtotime($next." +4 day");
        $next = date("Y-m-d",$next);

        $max = date("Y-m-d");
        $max = strtotime($max." +3 months");
        $max = date("Y-m-d",$max);

        $vehicules = $vehiculeRepository->findByModele();
        $setting = $settingsRepo->findFirst();
        usort($vehicules, function($a, $b){
            return count($a->getFeedback()) < count($b->getFeedback());
        });
        return $this->render('frontoffice/home.html.twig', [
            'agences' => $agenceRepository->findAll(),
            'vehicules' => $vehicules,
            'today' => $today,
            'next' => $next,
            'max' => $max,
            'setting' => $setting,
        ]);
    }

    /**
     * @Route("/pay", name="pay_index")
     */
    public function payment(PaymentRepository $paymentRepo,LocationRepository $locationRepo): Response
    {
        $user = $this->getUser();
        $payment = new Payment();
        $location = $locationRepo->findBy(["Num" => $_GET['Num']]);
        $payment->setSessionId("  ");
        $payment->setClient($user);
        $payment->setLocation($location[0]);
        $payment->setTotal($_GET['amount']);
        $date = date("m/d/Y");
        $payment->setCreatedOn(new \DateTime($date));
        $payment->setStatus("pending");
        $payment = $paymentRepo->add($payment,true);
        $response = $this->client->request('POST', 'https://api.konnect.network/api/v2/payments/init-payment', [
            'headers' => [
                'Accept' => 'application/json',
                'x-api-key' => $_ENV['PAYMENT_API_KEY'],
            ],
            'body' => [
                    "receiverWalletId" => $_ENV['PAYMENT_RECEIVER_WALLET'],
                    "amount" => $_GET['amount'] * 1000,
                    "selectedPaymentMethod" => "gateway",
                    "firstName" => $user->getName(),
                    "lastName" => $user->getLastName(),
                    "phoneNumber" => "+21658557726",
                    "token" => "TND",
                    "orderId" => $payment->getId(),
                    "successUrl" => $_ENV['APP_URL']."paymentsuccess/".$payment->getId(),
                    "failUrl" => $_ENV['APP_URL']."profile?paymentFail=true"
            ]
        ]);
        $content = json_decode($response->getContent(),true);
        print_r($content);
        $payment->setSessionId($content['paymentRef']);
        $payment = $paymentRepo->add($payment,true);
        $this->user = $user;
        return $this->redirect($content['payUrl']);

    }
    /**
     * @Route("/paymentsuccess/{id}", name="pay_success")
     */
    public function paymentSuccess($id,PaymentRepository $paymentRepo,LocationRepository $locationRepo, MailerInterface $mailer): Response
    {
        $user = $this->getUser();
        $response = $this->client->request('GET', 'https://api.konnect.network/api/v1/payments/'.$id, [
            'headers' => [
                'Accept' => 'application/json',
            ]
        ]);
        $content = json_decode($response->getContent(),true);
        $payment = $paymentRepo->findBy(["id" => $content["orderId"]])[0];
        print_r($content);
        if($payment->getStatus() == "pending" && $content["status"] == "paid") {
            $payment->setStatus("paid");
            $payment->setCreatedOn(new \DateTime("now"));
            $paymentRepo->add($payment, true);
            $location = $payment->getLocation();
            $location->setStatus("Payé");
            $location->setEtat("Confirmée");
            $locationRepo->add($location, true);
            $pendingLocations = $locationRepo->findBy(["Vehicule" => $location->getVehicule()->getId(),"Status" => "Non Confirmée"]);
            foreach( $pendingLocations as $loc) {
                if(strtotime($location->getDate_Loc()) >= strtotime($loc->getDate_Loc()) && strtotime($location->getDate_Loc()) <= strtotime($loc->getDate_Retour())) {
                    $loc->setStatus("Annulée");
                    $loc->setEtat("Annulée");
                    $locationRepo->add($loc, true);
                }
                if(strtotime($location->getDate_Retour()) >= strtotime($loc->getDate_Loc()) && strtotime($location->getDate_Retour()) <= strtotime($loc->getDate_Retour())) {
                    $loc->setStatus("Annulée");
                    $loc->setEtat("Annulée");
                    $locationRepo->add($loc, true);
                }
            }
            $email = (new TemplatedEmail())
            ->from(new Address('w311940@gmail.com', 'Makrent car'))
            ->to($user->getEmail())
            ->subject('Confirmation')
            ->htmlTemplate('email/successful-email.html.twig');

            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                
            }

            return $this->redirectToRoute('front_office_profile',["paymentSuccess" => true], Response::HTTP_SEE_OTHER);

        } else {
            $payment->setStatus($content["status"]);
            $payment->setCreatedOn(new \DateTime("now"));
            $paymentRepo->add($payment, true);
            return $this->redirectToRoute('front_office_profile',["paymentFail" => true], Response::HTTP_SEE_OTHER);
        }
    }
    /**
     * @Route("/profile", name="front_office_profile")
     */
    public function profile(FeedbackRepository $feedbackRepository, Request $request, SettingsRepository $settingsRepo): Response
    {
        if ($this->isGranted('ROLE_MODERATOR')) {
            return $this->redirectToRoute('dashboard_index');
        }
        if (!$this->getUser()) {
            $GET = $_GET;
            $GET["route"] = "front_office_profile";
            return $this->redirectToRoute('login', $GET);
        }
        $paymentSuccess = isset($_GET['paymentSuccess']);
        $paymentFail = isset($_GET['paymentFail']);
        $user = $this->getUser();
        $today = date('Y-m-d');
        $feedback = new Feedback();
        $form = $this->createForm(FeedbackType::class, $feedback);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $feedback->setClient($user);
            $feedback->setVehicule(null);
            $feedbackRepository->add($feedback, true);
            $this->user = $user;
            return $this->redirectToRoute('front_office_profile',[], Response::HTTP_SEE_OTHER);
        }
        $this->user = $user;
        $setting = $settingsRepo->findFirst();
        return $this->render('frontoffice/profile.html.twig', [
            'form' => $form->createView(),
            'today' => $today,
            'GET' => $_GET,
            'paymentSuccess' => $paymentSuccess,
            'paymentFail' => $paymentFail,
            'setting' => $setting
        ]);
    }
    /**
     * @Route("/signup", name="front_office_signup", methods={"GET", "POST"})
     */
    public function signup(Request $request, ClientRepository $clientRepository, NotificationRepository $notificationRepo, SettingsRepository $settingsRepo): Response
    {
        $user = $this->getUser();
        if($user) {
            return $this->redirectToRoute('home_index', [], Response::HTTP_SEE_OTHER);
        }
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        $today = date('Y-m-d');

        if ($form->isSubmitted() && $form->isValid()) {
            $client->setAdd1(" ");
            $client->setAdd2(" ");
            $client->setPassword($this->passwordHasher->hashPassword(
                $client,
                $form->get('password')->getData()
            ));

            $date = date('Y-m-d H:i:s');
            $notification = new Notification();
            $notification->setTitle("Nouvelle Client.");
            $notification->setBody($client->getEmail()." a créé un compte en tant que ".$client->getName()." ".$client->getLastName()." à ".$date);
            $notification->setCreatedOn(new \DateTime($date));
            $notification->setSeen(false);
            $notificationRepo->add($notification,true);
            $clientRepository->add($client, true);
            if(isset($_GET['GET']) ){
                return $this->redirectToRoute('login', $_GET['GET'], Response::HTTP_SEE_OTHER);

            } else {
                return $this->redirectToRoute('login', [], Response::HTTP_SEE_OTHER);
            }
        }
        $setting = $settingsRepo->findFirst();
        return $this->renderForm('frontoffice/signup.html.twig', [
            'client' => $client,
            'form' => $form,
            'today' => $today,
            'setting' => $setting
        ]);
    }
    /**
     * @Route("/profile/edit", name="front_office_profile_edit")
     */
    public function editProfile(Request $request, ClientRepository $clientRepository , SettingsRepository $settingsRepo) : Response
    {
        if ($this->isGranted('ROLE_MODERATOR')) {
            return $this->redirectToRoute('dashboard_index');
        }
        if (!$this->getUser()) {
            return $this->redirectToRoute('login');
        }
        $client = $this->get('security.token_storage')->getToken()->getUser();
        $client = $clientRepository->find(['id' => $client->getId()]);
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        if ($form->isSubmitted() ) {
            if($form->isValid())
            $client->setPassword($this->passwordHasher->hashPassword(
                $client,
                $form->get('password')->getData()
            ));

            $clientRepository->add($client, true);

            return $this->redirectToRoute('front_office_profile', [], Response::HTTP_SEE_OTHER);
        }
        $setting = $settingsRepo->findFirst();
        return $this->renderForm('frontoffice/edit.html.twig', [
            'client' => $client,
            'form' => $form,
            'setting' => $setting
        ]);
    }

    /**
     * @Route("/reservation/{Num}", name="front_office_reservation")
     */
    public function reservation($Num, LocationRepository $locationRepo, SettingsRepository $settingsRepo): Response
    {
        $user = $this->getUser();
        if ($this->isGranted('ROLE_MODERATOR')) {
            return $this->redirectToRoute('dashboard_index');
        }
        $today = date('Y-m-d');
        $location = $locationRepo->findBy(array("Num" => $Num));
        $location = $location[0];
        $vehicule = $location->getVehicule();
        $caut = $vehicule->getCaut();
        $park = $vehicule->getPark();
        $agence_d = $location->getAgenceDepart();
        $agence_r = $location->getAgenceArrive();
        $isBS = strtotime($park->getDebut_HS())>strtotime($today) || strtotime($park->getFin_HS())<strtotime($today);
        $amount = 0;
        $montant = $location->getMontant();
        if($isBS) {
            $amount = ($montant*50)/100;
        } else {
            $amount += $montant;
        }
        $DP = $location->getDate_Loc();
        $DD = $location->getDate_Retour();
        $date1 = new \DateTime($DP);
        $date2 = new \DateTime($DD);
        $interval = $date1->diff($date2);
        $days = $interval->days; 
        $this->user = $user;
        $setting = $settingsRepo->findFirst();
        return $this->render('frontoffice/reservation.html.twig', [
            "reservation" => $location,
            "amount" => $amount,
            "Days" => $days,
            'frais' => $agence_d->getFrais()+$agence_r->getFrais(),
            'BS' => $vehicule->getPark()->getPrixBabySeat(),
            'STW' => $vehicule->getPark()->getPrixSTW(),
            'PD' => $vehicule->getPark()->getPrixPersonalDriver(),
            'SD' => $vehicule->getPark()->getPrixSecondDriver(),
            'RS' => $vehicule->getReservoire(),
            'setting' => $setting
        ]);
    }

    /**
     * @Route("/search", name="front_office_search", methods={"GET", "POST"})
     */
    public function search(AgenceRepository $agenceRepository, VehiculeRepository $vehiculeRepository, LocationRepository $locationRepo, SettingsRepository $settingsRepo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isGranted('ROLE_MODERATOR')) {
            return $this->redirectToRoute('dashboard_index');
        }
        $today = date("Y-m-d");
        $today = strtotime($today." +1 day");
        $today = date("Y-m-d",$today);

        $next = date("Y-m-d");
        $next = strtotime($next." +4 day");
        $next = date("Y-m-d",$next);

        $max = date("Y-m-d");
        $max = strtotime($max." +3 months");
        $max = date("Y-m-d",$max);
                
        $formattedToday = date("m/d/Y");
        $formattedTodayYMD = date("Y-m-d");
        $DP = isset($_GET['DP']) ? strtotime($_GET['DP']) : $today;
        $DD = isset($_GET['DD']) ? strtotime($_GET['DD']) : $today;
        $DP2 = date("Y-m-d",$DP);
        $DD2 = date("Y-m-d",$DD);
        $vehicules_raw = $vehiculeRepository->findByModele();
        $vehicules = [];
        $marques = $vehiculeRepository->findByMarque();
        $Mq = [];
        $Bt = [];
        $isset_mq = isset($_GET['Mq']);
        $isset_bt = isset($_GET['Bt']);
        if($isset_mq) {
            foreach($_GET['Mq'] as $mq) {
                $Mq[$mq] = $mq;
            }
        }
        if($isset_bt) {
            foreach($_GET['Bt'] as $bt) {
                $Bt[$bt] = $bt;
            }
        }
        $start = $today;
        $formattedStart = $formattedToday;
        $dispoCarsArray = [];
        $otherCarsFoundArray = [];
        $otherCarsArray = [];
        $user = $this->getUser();
        foreach($vehicules_raw as $vehicule_raw) {
            $vehs = $vehiculeRepository->findBy(array("Modele" => $vehicule_raw->getModele(),'Dispo' => true));
            $dispoArray = [];
            $vehDispo = false;
            $push = false;
            $oldId = 0;
            $isMQ = false;
            $isBT = false;
            $countTotal = 0;
            foreach($vehs as $veh){
                if($oldId != $veh->getId()){
                    $oldId = $veh->getId();
                    $start = date('d/m/Y',strtotime($today));
                    $formattedStart = $formattedToday;
                }
                $locations = $locationRepo->findBy(array("Vehicule" => $veh->getId()),array('Date_Loc' => "ASC"));
                $countTotal += count($locations);
                foreach($locations as $location) {
                    if($location->getEtat() == "Confirmée" || $location->getEtat() == "En Cours") {
                        $formattedDP = $formattedStart;
                        $end = date("d/m/Y", strtotime($location->getDate_Loc()." - 1 days"));
                        if ($start != $end){
                            $formattedDD = date("m/d/Y", strtotime($location->getDate_Loc()." - 1 days"));
                            if(strtotime($formattedStart) >= strtotime($formattedToday) && strtotime($formattedStart) < strtotime($formattedDD)) {
                                    $disponibility = new Disponibility();
                                    $disponibility->setStart($start);
                                    $disponibility->setEnd($end);
                                    array_push($dispoArray,$disponibility);
                            }
                        }
                        $start = date('d/m/Y', strtotime($location->getDate_Retour(). ' + 1 days'));
                        $formattedStart = date('m/d/Y', strtotime($location->getDate_Retour(). ' + 1 days'));
                    }
                }
                $disponibility = new Disponibility();
                if(strtotime($formattedStart) >= strtotime($formattedToday)) {
                    $disponibility->setStart($start);
                } else {
                    $disponibility->setStart(date('d/m/Y',strtotime($today)));
                }
                array_push($dispoArray,$disponibility);
                if($DP >= strtotime($formattedStart)) {
                    $vehDispo = true;
                }
            }
            usort($dispoArray, function($a, $b) {
                $tmpA = DateTime::createFromFormat("d/m/Y", $a->getStart());
                $tmpB = DateTime::createFromFormat("d/m/Y", $b->getStart());
                return $tmpA > $tmpB;
            });
            $filtered =  Disponibility::getUnique($dispoArray);
            $dispoCarsArray[$vehicule_raw->getId()] = $filtered;
            if($countTotal == 0) {
                $vehDispo = true;
            }
            if(count($dispoCarsArray[$vehicule_raw->getId()]) == 0) {
                $vehDispo = false;
            }
            if($isset_mq && !isset($Mq[$vehicule_raw->getMarque()])) {
                $vehDispo = false;
                $isMQ = false;
            }

            if($isset_bt && !isset($Bt[$vehicule_raw->getBoite()])) {
                $vehDispo = false;
                $isBT = false;
            }
            if($vehDispo) {
                if(!$push) {
                    array_push($vehicules,$vehicule_raw);
                    $push = true;
                }
            } else if (isset($Mq[$vehicule_raw->getMarque()])){
                if(!$push) {
                    array_push($otherCarsFoundArray,$vehicule_raw);
                    $push = true;
                }
            } else if (isset($Bt[$vehicule_raw->getBoite()])){
                if(!$push) {
                    array_push($otherCarsFoundArray,$vehicule_raw);
                    $push = true;
                }
            } else {
                if(!$push) {
                    array_push($otherCarsArray,$vehicule_raw);
                    $push = true;
                }
            }
        }
        $date1 = new \DateTime($DP2);
        $date2 = new \DateTime($DD2);
        $interval = $date1->diff($date2);
        $days = $interval->days > 0 ? $interval->days : 1;
        $setting = $settingsRepo->findFirst();
        foreach($vehicules as $veh) {
            foreach($otherCarsArray as $key=>$otherCar) {
                if($veh->getModele() == $otherCar->getModele()) {
                    unset($otherCarsArray[$key]);
                }
            }
        }
        foreach($otherCarsArray as $key1=>$veh) {
            foreach($otherCarsArray as $key2=>$otherCar) {
                if($veh->getModele() == $otherCar->getModele()) {
                    if(array_key_exists($key1,$dispoCarsArray) && $veh->isDispo()) {
                        unset($otherCarsArray[$key2]);
                    } else if(array_key_exists($key2,$dispoCarsArray) && $otherCar->isDispo()){
                        unset($otherCarsArray[$key1]);
                    }
                }
            }
        }
        $this->user=$user;
        return $this->render('frontoffice/search.html.twig', [
            'agences' => $agenceRepository->findAll(),
            'vehicules' => $vehicules,
            'otherVehicules' => $otherCarsArray,
            'otherFoundVehicules' => $otherCarsFoundArray,
            'marques' => $marques,
            'Mq' => $Mq,
            'Bt' => $Bt,
            'dispo' => $dispoCarsArray,
            'GET' => $_GET,
            'today' => $formattedTodayYMD,
            'days' => $days,
            'setting' => $setting,
            'today' => $today,
            'next' => $next,
            'max' => $max,
        ]);
    }

    /**
     * @Route("/car/{id}", name="front_office_car")
     */
    public function car(Vehicule $vehicule, FeedbackRepository $feedbackRepository, AgenceRepository $agenceRepository, LocationRepository $locationRepo, VehiculeRepository $vehiculesRepo, Request $request, SettingsRepository $settingsRepo): Response
    {
        if ($this->isGranted('ROLE_MODERATOR')) {
            return $this->redirectToRoute('dashboard_index');
        }
        $today = date("Y-m-d");
        $today = strtotime($today." +1 day");
        $today = date("Y-m-d",$today);

        $next = date("Y-m-d");
        $next = strtotime($next." +4 day");
        $next = date("Y-m-d",$next);

        $max = date("Y-m-d");
        $max = strtotime($max." +3 months");
        $max = date("Y-m-d",$max);

        $formattedToday = date("m/d/Y");
        $formattedTodayYMD = date("Y-m-d");
        $formattedStart = $formattedToday;
        $dispoArray = [];
        $dispo = true;
        $user = $this->getUser();
        $feedback = new Feedback();
        $form = $this->createForm(FeedbackType::class, $feedback);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $feedback->setClient($user);
            $feedback->setVehicule($vehicule);
            $feedbackRepository->add($feedback, true);
            $this->user = $user;
            return $this->redirectToRoute('front_office_car', array('id' => $vehicule->getId()), Response::HTTP_SEE_OTHER);
        }
        $locations = [];
        $vehicules = $vehiculesRepo->findBy(array("Modele" => $vehicule->getModele(),"Dispo" => true));
        foreach($vehicules as $veh) {
            $start = date('d/m/Y',strtotime($today));
            foreach( $veh->getLocations() as $location) {
                if($location->getEtat() == "Confirmée" || $location->getEtat() == "En Cours") {
                    $formattedDP = $formattedStart;
                    $end = date("d/m/Y", strtotime($location->getDate_Loc()." - 1 days"));
                    if ($start != $end){
                        $formattedDD = date("m/d/Y", strtotime($location->getDate_Loc()." - 1 days"));
                        if(strtotime($formattedStart) >= strtotime($formattedToday) && strtotime($formattedStart) < strtotime($formattedDD)) {
                                $disponibility = new Disponibility();
                                $disponibility->setStart($start);
                                $disponibility->setEnd($end);
                                array_push($dispoArray,$disponibility);
                        }
                    }
                    $start = date('d/m/Y', strtotime($location->getDate_Retour(). ' + 1 days'));
                    $formattedStart = date('m/d/Y', strtotime($location->getDate_Retour(). ' + 1 days'));
                   
                }
            }
            $disponibility = new Disponibility();
            if(strtotime($formattedStart) >= strtotime($formattedToday)) {
                $disponibility->setStart($start);
            } else {
                $disponibility->setStart(date('d/m/Y',strtotime($today)));
            }
            array_push($dispoArray,$disponibility);

        }
        usort($dispoArray, function($a, $b) {
            return strtotime($a->getStart()) - strtotime($b->getStart());
        });
        $filtered =  Disponibility::getUnique($dispoArray);
        $fbVehs = $vehiculesRepo->findBy(array('Modele' => $vehicule->getModele()));
        $feedbacks=[];
        foreach($fbVehs as $fbVeh) {
            $fbs = $feedbackRepository->findBy(array('Vehicule' => $fbVeh->getId(), "Visible" => true));
            foreach($fbs as $fb) {
                    array_push($feedbacks,$fb);
            }
        }
        $this->user = $user;
        $setting = $settingsRepo->findFirst();
        return $this->render('frontoffice/car.html.twig', [
            'vehicule' => $vehicule,
            'feedbacks' => $feedbacks,
            'agences' => $agenceRepository->findAll(),
            'form' => $form->createView(),
            'today' => $formattedTodayYMD,
            'dispo' => $filtered,
            'GET' => $_GET,
            'setting' => $setting,
            'today' => $today,
            'next' => $next,
            'max' => $max,
        ]);
    }

    /**
     * @Route("/cars", name="front_office_cars")
     */
    public function cars( VehiculeRepository $vehiculeRepository, SettingsRepository $settingsRepo): Response
    {
        if ($this->isGranted('ROLE_MODERATOR')) {
            return $this->redirectToRoute('dashboard_index');
        }
        $Mq = [];
        $isset_mq = isset($_GET['Mq']);
        if($isset_mq) {
            foreach($_GET['Mq'] as $mq) {
                $Mq[$mq] = $mq;
            }
        }

        $Bt = [];
        $isset_bt = isset($_GET['Bt']);
        if($isset_bt) {
            foreach($_GET['Bt'] as $bt) {
                $Bt[$bt] = $bt;
            }
        }
        $marques = $vehiculeRepository->findByMarque();
        $vehicules = $vehiculeRepository->findByModele();
        $vehsDispo = [];
        foreach($vehicules as $vehicule) {
            if($isset_mq && isset($Mq[$vehicule->getMarque()])) {
                if($isset_bt && isset($Bt[$vehicule->getBoite()])) {
                    array_push($vehsDispo, $vehicule);
                } else if(!$isset_bt) {
                    array_push($vehsDispo, $vehicule);
                }
            } else if(!$isset_mq) {
                if($isset_bt && isset($Bt[$vehicule->getBoite()])) {
                    array_push($vehsDispo, $vehicule);
                } else if(!$isset_bt) {
                    array_push($vehsDispo, $vehicule);
                }
            }

        }
        $setting = $settingsRepo->findFirst();
        return $this->render('frontoffice/cars.html.twig', [
            'vehicules' => $vehsDispo,
            'marques' => $marques,
            'Mq' => $Mq,
            'Bt' => $Bt,
            'setting' => $setting,
        ]);
    }
    /**
     * @Route("/booking/{id}", name="front_office_booking", methods={"GET", "POST"})
     */
    public function booking( Vehicule $vehicule, LocationRepository $locationRepository,VehiculeRepository $vehiculesRepo, Request $request, SettingsRepository $settingsRepo): Response
    {
        if ($this->isGranted('ROLE_MODERATOR')) {
            return $this->redirectToRoute('dashboard_index');
        }
        $user = $this->getUser();
        $today = date('Y-m-d');
        $location = new Location();
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);
        if(!isset($_GET['DP']) || empty($_GET['DP']) || !isset($_GET['DD']) || empty($_GET['DD'])) {
            $this->user = $user;
            return $this->redirectToRoute('front_office_car', ['id'=> $vehicule->getId(),'AD' => $_GET['AD'],'AP' => $_GET['AP'],'DD' => $_GET['DD'],'DP' => $_GET['DP']], Response::HTTP_SEE_OTHER);
        }
        $DP = strtotime($_GET['DP']);
        $DD = strtotime($_GET['DD']);

        if(strtotime($today) > $DP) {
            $this->user = $user;
            return $this->redirectToRoute('front_office_car', ['id'=> $vehicule->getId(),'AD' => $_GET['AD'],'AP' => $_GET['AP'],'DD' => $_GET['DD'],'DP' => $_GET['DP']], Response::HTTP_SEE_OTHER);
        }
        
        $vehicules = $vehiculesRepo->findBy(array("Modele" => $vehicule->getModele(),"Dispo" => true));
        $dispo = false;
        foreach($vehicules as $veh) {
            $dispo = true;
            foreach($veh->getLocations() as $location) { 
                if($location->getEtat() != "Non Confirmée" && $location->getEtat() != "Annulée") {
                    $location_DP = strtotime($location->getDate_Loc());
                    $location_DD = strtotime($location->getDate_Retour());
                    if($location_DP<=$DP && $location_DD>=$DP) {
                        $dispo = false;
                    }
                    if($location_DP<=$DD && $location_DD>=$DD) {
                        $dispo = false;
                    }
                }
            }
            if($dispo) {
                $this->user = $user;
                $setting = $settingsRepo->findFirst();
                return $this->render('frontoffice/booking.html.twig', [
                    'vehicule' => $veh,
                    'form' => $form->createView(),
                    'GET' => $_GET,
                    'today' => $today,
                    'DP' => date("Y-m-d",$DP),
                    'DD' => date("Y-m-d",$DD),
                    'BS' => $veh->getPark()->getPrixBabySeat(),
                    'STW' => $veh->getPark()->getPrixSTW(),
                    'PD' => $veh->getPark()->getPrixPersonalDriver(),
                    'SD' => $veh->getPark()->getPrixSecondDriver(),
                    'RS' => $veh->getReservoire(),
                    'setting' => $setting,
                ]); 
            }
        }
        if(!$dispo) {
            $this->user = $user;
            return $this->redirectToRoute('front_office_car', ['id'=> $vehicule->getId(),'AD' => $_GET['AD'],'AP' => $_GET['AP'],'DD' => $_GET['DD'],'DP' => $_GET['DP'], 'err' => "Véhicule indisponible pendant la durée spécifiée"], Response::HTTP_SEE_OTHER);
        }
    }

    /**
     * @Route("/preview/{id}", name="front_office_preview", methods={"GET", "POST"})
     */
    public function preview( Vehicule $vehicule, LocationRepository $locationRepository, AgenceRepository $agenceRepository, PromoRepository $promoRepository,NotificationRepository $notificationRepo, Request $request, SettingsRepository $settingsRepo, BlacklistRepository $blacklistRepo): Response
    {
        if ($this->isGranted('ROLE_MODERATOR')) {
            return $this->redirectToRoute('dashboard_index');
        }
        if (!$this->getUser()) {
            $GET = $_GET;
            $GET["route"] = "front_office_preview";
            $GET["id"] = $vehicule->getId();
            return $this->redirectToRoute('login', $GET);
        }
        $user = $this->getUser();
        $today = date('Y-m-d');
        $caut = $vehicule->getCaut();
        $park = $vehicule->getPark();
        $isBS = strtotime($park->getFin_BS()) >= strtotime($_GET['DP']);
        $location = new Location();
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);
        if ($form->isSubmitted() ) {
            $location->setVehicule($vehicule);
            $location->setClient($this->getUser());
            $location->setDateRes(new \DateTime());
            $location->setDateLoc(new \DateTime($location->getDate_Loc()));
            $location->setDateRetour(new \DateTime($location->getDate_Retour()));
            $montant = $location->getMontant();
            if($form->isValid()) {
                $client = $location->getClient();
                if( strtotime($today." - 2 years") < strtotime($client->getDatePermis()->format('Y-m-d'))) {
                    $this->user = $user;
                    return $this->redirectToRoute('front_office_preview', [
                        'id' => $vehicule->getId(),
                        'BS' => $_GET['BS'],
                        'STW' => $_GET['STW'],
                        'PD' => $_GET['PD'],
                        'SD' => $_GET['SD'],
                        'RS' => $_GET['RS'],
                        'DP' => $_GET['DP'],
                        'DD' => $_GET['DD'],
                        'AD' => $_GET['AD'],
                        'AP' => $_GET['AP'],
                        'err' => 'Le permis doit avoir au moins 2 ans'
                    ], Response::HTTP_SEE_OTHER);              
                } else if (strtotime($today." - 25 years") < strtotime($client->getDateNaissance()->format('Y-m-d')) ) {
                    $this->user = $user;
                    return $this->redirectToRoute('front_office_preview', [
                        'id' => $vehicule->getId(),
                        'BS' => $_GET['BS'],
                        'STW' => $_GET['STW'],
                        'PD' => $_GET['PD'],
                        'SD' => $_GET['SD'],
                        'RS' => $_GET['RS'],
                        'DP' => $_GET['DP'],
                        'DD' => $_GET['DD'],
                        'AD' => $_GET['AD'],
                        'AP' => $_GET['AP'],
                        'err' => 'Vous devez avoir au moins 25 ans'
                    ], Response::HTTP_SEE_OTHER);
                } else {
                    $blacklist = $blacklistRepo->findOneBy(array("CIN" => $user->getCIN()));
                    if($blacklist) {
                        $this->user = $user;
                        return $this->redirectToRoute('front_office_preview', [
                            'id' => $vehicule->getId(),
                            'BS' => $_GET['BS'],
                            'STW' => $_GET['STW'],
                            'PD' => $_GET['PD'],
                            'SD' => $_GET['SD'],
                            'RS' => $_GET['RS'],
                            'DP' => $_GET['DP'],
                            'DD' => $_GET['DD'],
                            'AD' => $_GET['AD'],
                            'AP' => $_GET['AP'],
                            'err' => 'Vous êtes dans la liste noire, vous ne pouvez pas réserver de véhicule'
                        ], Response::HTTP_SEE_OTHER);            
                    } else {
                        $blacklist = $blacklistRepo->findOneBy(array("Permis" => $user->getPermis()));
                        if($blacklist) {
                            $this->user = $user;
                            return $this->redirectToRoute('front_office_preview', [
                                'id' => $vehicule->getId(),
                                'BS' => $_GET['BS'],
                                'STW' => $_GET['STW'],
                                'PD' => $_GET['PD'],
                                'SD' => $_GET['SD'],
                                'RS' => $_GET['RS'],
                                'DP' => $_GET['DP'],
                                'DD' => $_GET['DD'],
                                'AD' => $_GET['AD'],
                                'AP' => $_GET['AP'],
                                'GET'=> $_GET,
                                'err' => 'Vous êtes dans la liste noire, vous ne pouvez pas réserver de véhicule'
                            ], Response::HTTP_SEE_OTHER); 
                        }
                    }
                    if($_GET['SD'] == 1) {
                        $blacklist = $blacklistRepo->findOneBy(array("CIN" => $location->getSecondDriverCIN()));
                        if($blacklist) {
                            $this->user = $user;
                            return $this->redirectToRoute('front_office_preview', [
                                'id' => $vehicule->getId(),
                                'BS' => $_GET['BS'],
                                'STW' => $_GET['STW'],
                                'PD' => $_GET['PD'],
                                'SD' => $_GET['SD'],
                                'RS' => $_GET['RS'],
                                'DP' => $_GET['DP'],
                                'DD' => $_GET['DD'],
                                'AD' => $_GET['AD'],
                                'AP' => $_GET['AP'],
                                'GET'=> $_GET,
                                'err' => 'Deuxième conducteur est dans la liste noire, vous ne pouvez pas réserver de véhicule'
                            ], Response::HTTP_SEE_OTHER);            
                        } else {
                            $blacklist = $blacklistRepo->findOneBy(array("Permis" => $location->getSecondDriverPermis()));
                            if($blacklist) {
                                $this->user = $user;
                                return $this->redirectToRoute('front_office_preview', [
                                    'id' => $vehicule->getId(),
                                    'BS' => $_GET['BS'],
                                    'STW' => $_GET['STW'],
                                    'PD' => $_GET['PD'],
                                    'SD' => $_GET['SD'],
                                    'RS' => $_GET['RS'],
                                    'DP' => $_GET['DP'],
                                    'DD' => $_GET['DD'],
                                    'AD' => $_GET['AD'],
                                    'AP' => $_GET['AP'],
                                    'err' => 'Deuxième conducteur est dans la liste noire, vous ne pouvez pas réserver de véhicule'
                                ], Response::HTTP_SEE_OTHER); 
                            }
                        }

                        if( strtotime($today." - 2 years") < strtotime($location->getSecondDriverDatePermis()->format('Y-m-d'))) {
                            $this->user = $user;
                            return $this->redirectToRoute('front_office_preview', [
                                'id' => $vehicule->getId(),
                                'BS' => $_GET['BS'],
                                'STW' => $_GET['STW'],
                                'PD' => $_GET['PD'],
                                'SD' => $_GET['SD'],
                                'RS' => $_GET['RS'],
                                'DP' => $_GET['DP'],
                                'DD' => $_GET['DD'],
                                'AD' => $_GET['AD'],
                                'AP' => $_GET['AP'],
                                'err' => 'Le permis doit du deuxième conducteur avoir au moins 2 ans'
                            ], Response::HTTP_SEE_OTHER);              
                        } else if (strtotime($today." - 25 years") < strtotime($location->getSecondDriverDN()->format('Y-m-d')) ) {
                            $this->user = $user;
                            return $this->redirectToRoute('front_office_preview', [
                                'id' => $vehicule->getId(),
                                'BS' => $_GET['BS'],
                                'STW' => $_GET['STW'],
                                'PD' => $_GET['PD'],
                                'SD' => $_GET['SD'],
                                'RS' => $_GET['RS'],
                                'DP' => $_GET['DP'],
                                'DD' => $_GET['DD'],
                                'AD' => $_GET['AD'],
                                'AP' => $_GET['AP'],
                                'err' => 'Le deuxième conducteur devez avoir au moins 25 ans'
                            ], Response::HTTP_SEE_OTHER);
                        }
                    }
                    $date = date('Y-m-d H:i:s');
                    $notification = new Notification();
                    $notification->setTitle("Nouvelle Reservation.");
                    $notification->setBody($user->getEmail()."|".$user->getName()." ".$user->getLastName()." a fait une nouvelle reservation à ".$date);
                    $notification->setCreatedOn(new \DateTime($date));
                    $notification->setSeen(false);
                    $notificationRepo->add($notification,true);
                    $this->user = $user;
                    if($_POST['METHD'] == "EL") {
                        $location->setType('En ligne');
                        $location->setAvance($montant);
                        $loc = $locationRepository->add($location, true);
                        return $this->redirectToRoute('pay_index',["amount" => $montant,"Num" => $location->getNum()], Response::HTTP_SEE_OTHER);
                    } else {
                        $location->setType("À l'agence");
                        $location->setAvance($montant/5);
                        $loc = $locationRepository->add($location, true);
                        return $this->redirectToRoute('pay_index',["amount" => $montant/5,"Num" => $location->getNum()], Response::HTTP_SEE_OTHER);
                    }
                }
                
            }
        } else {
            if(!isset($_GET['DP']) || empty($_GET['DP']) || !isset($_GET['DD']) || empty($_GET['DD']) || !isset($_GET['AP']) || empty($_GET['AP']) 
            || !isset($_GET['AD']) || empty($_GET['AD']) || !isset($_GET['BS']) || !isset($_GET['STW']) 
            || !isset($_GET['SD']) || !isset($_GET['PD']) ){
                $this->user = $user;
                return $this->redirectToRoute('front_office_car', ['id' => $vehicule->getId()], Response::HTTP_SEE_OTHER);
            } else {
                $agence_d = $agenceRepository->find($_GET['AP']);
                $agence_r = $agenceRepository->find($_GET['AD']);
                $DP = strtotime($_GET['DP']);
                $DD = strtotime($_GET['DD']);

                $DP = date("Y-m-d",$DP);
                $DD = date("Y-m-d",$DD);
                
                $today = date('Y-m-d');

                $date1 = new \DateTime($DP);
                $date2 = new \DateTime($DD);
                $interval = $date1->diff($date2);

                if($isBS) {
                    $prix = $vehicule->getPrix();
                } else {
                    $prix = $vehicule->getPrixHS();
                }
                $optionsPrice = 0;
                $prix = $_GET['BS'] ? $prix+$vehicule->getPark()->getPrixBabySeat() : $prix ;
                $optionsPrice += $_GET['BS'] ? $vehicule->getPark()->getPrixBabySeat() : 0 ;
                $prix = $_GET['PD'] ? $prix+$vehicule->getPark()->getPrixPersonalDriver() : $prix ;
                $optionsPrice += $_GET['PD'] ? $vehicule->getPark()->getPrixPersonalDriver() : 0 ;
                if(isset($_GET['Promo'])) {
                    $promo = $promoRepository->findOneBy(['Code' => $_GET['Promo'] ]);
                    if(!empty($promo)) {
                        $prix = $prix - ($prix * $promo->getPourcentage())/100;
                    }
                }

                $days = $interval->days > 0 ? $interval->days : 1;
                $prix = $prix * ($days);
                $optionsPrice = $optionsPrice * ($days);
                $prix = $_GET['RS'] ? $prix+$vehicule->getReservoire() : $prix;
                $prix = $_GET['STW'] ? $prix+$vehicule->getPark()->getPrixSTW() : $prix ;
                $prix = $_GET['SD'] ? $prix+$vehicule->getPark()->getPrixSecondDriver() : $prix ;
                $optionsPrice += $_GET['STW'] ? $vehicule->getPark()->getPrixSTW() : 0 ;
                $optionsPrice += $_GET['SD'] ? $vehicule->getPark()->getPrixSecondDriver() : 0 ;
                $optionsPrice += $_GET['RS'] ? $vehicule->getReservoire() : 0 ;
                $prix += $agence_d->getFrais();
                $prix += $agence_r->getFrais();
                $optionsPrice += $agence_d->getFrais();
                $optionsPrice += $agence_r->getFrais();
                $setting = $settingsRepo->findFirst();
                return $this->render('frontoffice/preview.html.twig', [
                    'vehicule' => $vehicule,
                    'form' => $form->createView(),
                    'GET' => $_GET,
                    'agence_d' => $agence_d->getNom(),
                    'agence_r' => $agence_r->getNom(),
                    'today' => $today,
                    'DP' => $DP,
                    'DD' => $DD,
                    'prix' => $prix,
                    'optionsPrice' => $optionsPrice,
                    'isBS' => $isBS,
                    'frais' => $agence_d->getFrais()+$agence_r->getFrais(),
                    'BS' => $vehicule->getPark()->getPrixBabySeat(),
                    'STW' => $vehicule->getPark()->getPrixSTW(),
                    'PD' => $vehicule->getPark()->getPrixPersonalDriver(),
                    'SD' => $vehicule->getPark()->getPrixSecondDriver(),
                    'RS' => $vehicule->getReservoire(),
                    'Days' => $days,
                    'setting' => $setting,
                ]);  
            }
        }
    }

    /**
     * @Route("/auto/{id}", name="front_office_auto")
     */
    public function auto(Auto $vehicule, FeedbackRepository $feedbackRepository, AgenceRepository $agenceRepository, LocationRepository $locationRepo, VehiculeRepository $vehiculesRepo, Request $request, SettingsRepository $settingsRepo): Response
    {
        if ($this->isGranted('ROLE_MODERATOR')) {
            return $this->redirectToRoute('dashboard_index');
        }
        $user = $this->getUser();
        $setting = $settingsRepo->findFirst();
        $this->user = $user;
        return $this->render('frontoffice/auto.html.twig', [
            'auto' => $vehicule,
            'setting' => $setting,
        ]);
    }

    /**
     * @Route("/autos", name="front_office_autos")
     */
    public function autos( AutoRepository $vehiculeRepository, SettingsRepository $settingsRepo): Response
    {
        if ($this->isGranted('ROLE_MODERATOR')) {
            return $this->redirectToRoute('dashboard_index');
        }
        $Mq = [];
        $isset_mq = isset($_GET['Mq']);
        if($isset_mq) {
            foreach($_GET['Mq'] as $mq) {
                $Mq[$mq] = $mq;
            }
        }

        $Bt = [];
        $isset_bt = isset($_GET['Bt']);
        if($isset_bt) {
            foreach($_GET['Bt'] as $bt) {
                $Bt[$bt] = $bt;
            }
        }
        $marques = $vehiculeRepository->findByMarque();
        $vehicules = $vehiculeRepository->findByModele();
        $vehsDispo = [];
        foreach($vehicules as $vehicule) {
            if($isset_mq && isset($Mq[$vehicule->getMarque()])) {
                if($isset_bt && isset($Bt[$vehicule->getBoite()])) {
                    array_push($vehsDispo, $vehicule);
                } else if(!$isset_bt) {
                    array_push($vehsDispo, $vehicule);
                }
            } else if(!$isset_mq) {
                if($isset_bt && isset($Bt[$vehicule->getBoite()])) {
                    array_push($vehsDispo, $vehicule);
                } else if(!$isset_bt) {
                    array_push($vehsDispo, $vehicule);
                }
            }

        }
        $setting = $settingsRepo->findFirst();
        return $this->render('frontoffice/autos.html.twig', [
            'autos' => $vehsDispo,
            'marques' => $marques,
            'Mq' => $Mq,
            'Bt' => $Bt,
            'setting' => $setting,
        ]);
    }

    
}
