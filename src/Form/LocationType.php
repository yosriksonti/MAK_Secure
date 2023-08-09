<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Location;
use App\Entity\Vehicule;
use App\Entity\Agence;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Num')
            ->add('IP', ChoiceType::class, [
                'choices'  => [
                    "Afghanistan" => "Afghanistan",
                    "Åland Islands" => "Åland Islands",
                    "Albania" => "Albania",
                    "Algeria" => "Algeria",
                    "American Samoa" => "American Samoa",
                    "Andorra" => "Andorra",
                    "Angola" => "Angola",
                    "Anguilla" => "Anguilla",
                    "Antarctica" => "Antarctica",
                    "Antigua and Barbuda" => "Antigua and Barbuda",
                    "Argentina" => "Argentina",
                    "Armenia" => "Armenia",
                    "Aruba" => "Aruba",
                    "Australia" => "Australia",
                    "Austria" => "Austria",
                    "Azerbaijan" => "Azerbaijan",
                    "Bahamas" => "Bahamas",
                    "Bahrain" => "Bahrain",
                    "Bangladesh" => "Bangladesh",
                    "Barbados" => "Barbados",
                    "Belarus" => "Belarus",
                    "Belgium" => "Belgium",
                    "Belize" => "Belize",
                    "Benin" => "Benin",
                    "Bermuda" => "Bermuda",
                    "Bhutan" => "Bhutan",
                    "Bolivia" => "Bolivia",
                    "Bosnia and Herzegovina" => "Bosnia and Herzegovina",
                    "Botswana" => "Botswana",
                    "Bouvet Island" => "Bouvet Island",
                    "Brazil" => "Brazil",
                    "British Indian Ocean Territory" => "British Indian Ocean Territory",
                    "Brunei Darussalam" => "Brunei Darussalam",
                    "Bulgaria" => "Bulgaria",
                    "Burkina Faso" => "Burkina Faso",
                    "Burundi" => "Burundi",
                    "Cambodia" => "Cambodia",
                    "Cameroon" => "Cameroon",
                    "Canada" => "Canada",
                    "Cape Verde" => "Cape Verde",
                    "Cayman Islands" => "Cayman Islands",
                    "Central African Republic" => "Central African Republic",
                    "Chad" => "Chad",
                    "Chile" => "Chile",
                    "China" => "China",
                    "Christmas Island" => "Christmas Island",
                    "Cocos (Keeling) Islands" => "Cocos (Keeling) Islands",
                    "Colombia" => "Colombia",
                    "Comoros" => "Comoros",
                    "Congo" => "Congo",
                    "Congo, The Democratic Republic of The" => "Congo, The Democratic Republic of The",
                    "Cook Islands" => "Cook Islands",
                    "Costa Rica" => "Costa Rica",
                    "Cote D'ivoire" => "Cote D'ivoire",
                    "Croatia" => "Croatia",
                    "Cuba" => "Cuba",
                    "Cyprus" => "Cyprus",
                    "Czech Republic" => "Czech Republic",
                    "Denmark" => "Denmark",
                    "Djibouti" => "Djibouti",
                    "Dominica" => "Dominica",
                    "Dominican Republic" => "Dominican Republic",
                    "Ecuador" => "Ecuador",
                    "Egypt" => "Egypt",
                    "El Salvador" => "El Salvador",
                    "Equatorial Guinea" => "Equatorial Guinea",
                    "Eritrea" => "Eritrea",
                    "Estonia" => "Estonia",
                    "Ethiopia" => "Ethiopia",
                    "Falkland Islands(Malvinas)" => "Falkland Islands (Malvinas)",
                    "Faroe Islands" => "Faroe Islands",
                    "Fiji" => "Fiji",
                    "Finland" => "Finland",
                    "France" => "France",
                    "French Guiana" => "French Guiana",
                    "French Polynesia" => "French Polynesia",
                    "French Southern Territories" => "French Southern Territories",
                    "Gabon" => "Gabon",
                    "Gambia" => "Gambia",
                    "Georgia" => "Georgia",
                    "Germany" => "Germany",
                    "Ghana" => "Ghana",
                    "Gibraltar" => "Gibraltar",
                    "Greece" => "Greece",
                    "Greenland" => "Greenland",
                    "Grenada" => "Grenada",
                    "Guadeloupe" => "Guadeloupe",
                    "Guam" => "Guam",
                    "Guatemala" => "Guatemala",
                    "Guernsey" => "Guernsey",
                    "Guinea" => "Guinea",
                    "Guinea-bissau" => "Guinea-bissau",
                    "Guyana" => "Guyana",
                    "Haiti" => "Haiti",
                    "Heard Island and Mcdonald Islands" => "Heard Island and Mcdonald Islands",
                    "Holy See(Vatican City State)" => "Holy See (Vatican City State)",
                    "Honduras" => "Honduras",
                    "Hong Kong" => "Hong Kong",
                    "Hungary" => "Hungary",
                    "Iceland" => "Iceland",
                    "India" => "India",
                    "Indonesia" => "Indonesia",
                    "Iran,Islamic Republic of" => "Iran, Islamic Republic of",
                    "Iraq" => "Iraq",
                    "Ireland" => "Ireland",
                    "Isle of Man" => "Isle of Man",
                    "Israel" => "Israel",
                    "Italy" => "Italy",
                    "Jamaica" => "Jamaica",
                    "Japan" => "Japan",
                    "Jersey" => "Jersey",
                    "Jordan" => "Jordan",
                    "Kazakhstan" => "Kazakhstan",
                    "Kenya" => "Kenya",
                    "Kiribati" => "Kiribati",
                    "Korea,Democratic People's Republic of" => "Korea, Democratic People's Republic of",
                    "Korea, Republic of" => "Korea,Republic of",
                    "Kuwait" => "Kuwait",
                    "Kyrgyzstan" => "Kyrgyzstan",
                    "Lao People's Democratic Republic" => "Lao People's Democratic Republic",
                    "Latvia" => "Latvia",
                    "Lebanon" => "Lebanon",
                    "Lesotho" => "Lesotho",
                    "Liberia" => "Liberia",
                    "Libyan Arab Jamahiriya" => "Libyan Arab Jamahiriya",
                    "Liechtenstein" => "Liechtenstein",
                    "Lithuania" => "Lithuania",
                    "Luxembourg" => "Luxembourg",
                    "Macao" => "Macao",
                    "Macedonia, The Former Yugoslav Republic of" => "Macedonia, The Former Yugoslav Republic of",
                    "Madagascar" => "Madagascar",
                    "Malawi" => "Malawi",
                    "Malaysia" => "Malaysia",
                    "Maldives" => "Maldives",
                    "Mali" => "Mali",
                    "Malta" => "Malta",
                    "Marshall Islands" => "Marshall Islands",
                    "Martinique" => "Martinique",
                    "Mauritania" => "Mauritania",
                    "Mauritius" => "Mauritius",
                    "Mayotte" => "Mayotte",
                    "Mexico" => "Mexico",
                    "Micronesia, Federated States of" => "Micronesia, Federated States of",
                    "Moldova, Republic of" => "Moldova, Republic of",
                    "Monaco" => "Monaco",
                    "Mongolia" => "Mongolia",
                    "Montenegro" => "Montenegro",
                    "Montserrat" => "Montserrat",
                    "Morocco" => "Morocco",
                    "Mozambique" => "Mozambique",
                    "Myanmar" => "Myanmar",
                    "Namibia" => "Namibia",
                    "Nauru" => "Nauru",
                    "Nepal" => "Nepal",
                    "Netherlands" => "Netherlands",
                    "Netherlands Antilles" => "Netherlands Antilles",
                    "New Caledonia" => "New Caledonia",
                    "New Zealand" => "New Zealand",
                    "Nicaragua" => "Nicaragua",
                    "Niger" => "Niger",
                    "Nigeria" => "Nigeria",
                    "Niue" => "Niue",
                    "Norfolk Island" => "Norfolk Island",
                    "Northern Mariana Islands" => "Northern Mariana Islands",
                    "Norway" => "Norway",
                    "Oman" => "Oman",
                    "Pakistan" => "Pakistan",
                    "Palau" => "Palau",
                    "Palestinian Territory, Occupied" => "Palestinian Territory, Occupied",
                    "Panama" => "Panama",
                    "Papua New Guinea" => "Papua New Guinea",
                    "Paraguay" => "Paraguay",
                    "Peru" => "Peru",
                    "Philippines" => "Philippines",
                    "Pitcairn" => "Pitcairn",
                    "Poland" => "Poland",
                    "Portugal" => "Portugal",
                    "Puerto Rico" => "Puerto Rico",
                    "Qatar" => "Qatar",
                    "Reunion" => "Reunion",
                    "Romania" => "Romania",
                    "Russian Federation" => "Russian Federation",
                    "Rwanda" => "Rwanda",
                    "Saint Helena" => "Saint Helena",
                    "Saint Kitts and Nevis" => "Saint Kitts and Nevis",
                    "Saint Lucia" => "Saint Lucia",
                    "Saint Pierre and Miquelon" => "Saint Pierre and Miquelon",
                    "Saint Vincent and The Grenadines" => "Saint Vincent and The Grenadines",
                    "Samoa" => "Samoa",
                    "San Marino" => "San Marino",
                    "Sao Tome and Principe" => "Sao Tome and Principe",
                    "Saudi Arabia" => "Saudi Arabia",
                    "Senegal" => "Senegal",
                    "Serbia" => "Serbia",
                    "Seychelles" => "Seychelles",
                    "Sierra Leone" => "Sierra Leone",
                    "Singapore" => "Singapore",
                    "Slovakia" => "Slovakia",
                    "Slovenia" => "Slovenia",
                    "Solomon Islands" => "Solomon Islands",
                    "Somalia" => "Somalia",
                    "South Africa" => "South Africa",
                    "South Georgia and The South Sandwich Islands" => "South Georgia and The South Sandwich Islands",
                    "Spain" => "Spain",
                    "Sri Lanka" => "Sri Lanka",
                    "Sudan" => "Sudan",
                    "Suriname" => "Suriname",
                    "Svalbard and Jan Mayen" => "Svalbard and Jan Mayen",
                    "Swaziland" => "Swaziland",
                    "Sweden" => "Sweden",
                    "Switzerland" => "Switzerland",
                    "Syrian Arab Republic" => "Syrian Arab Republic",
                    "Taiwan, Province of China" => "Taiwan, Province of China",
                    "Tajikistan" => "Tajikistan",
                    "Tanzania, United Republic of" => "Tanzania, United Republic of",
                    "Thailand" => "Thailand",
                    "Timor-leste" => "Timor-leste",
                    "Togo" => "Togo",
                    "Tokelau" => "Tokelau",
                    "Tonga" => "Tonga",
                    "Trinidad and Tobago" => "Trinidad and Tobago",
                    "Tunisia" => "Tunisia",
                    "Turkey" => "Turkey",
                    "Turkmenistan" => "Turkmenistan",
                    "Turks and Caicos Islands" => "Turks and Caicos Islands",
                    "Tuvalu" => "Tuvalu",
                    "Uganda" => "Uganda",
                    "Ukraine" => "Ukraine",
                    "United Arab Emirates" => "United Arab Emirates",
                    "United Kingdom" => "United Kingdom",
                    "United States" => "United States",
                    "United States Minor Outlying Islands" => "United States Minor Outlying Islands",
                    "Uruguay" => "Uruguay",
                    "Uzbekistan" => "Uzbekistan",
                    "Vanuatu" => "Vanuatu",
                    "Venezuela" => "Venezuela",
                    "Viet Nam" => "Viet Nam",
                    "Virgin Islands, British" => "Virgin Islands, British",
                    "Virgin Islands, U.S." => "Virgin Islands, U.S.",
                    "Wallis and Futuna" => "Wallis and Futuna",
                    "Western Sahara" => "Western Sahara",
                    "Yemen" => "Yemen",
                    "Zambia" => "Zambia",
                    "Zimbabwe" => "Zimbabwe",
                ]
            ])
            ->add('Date_Res', DateType::class, [ 
                'widget' => 'single_text',
                ])
            ->add('Date_Loc', DateType::class, [ 
                'widget' => 'single_text',
                ])
            ->add('Date_Retour', DateType::class, [ 
                'widget' => 'single_text',
                ])
            ->add('Montant')
            ->add('Avance')
            ->add('Type',ChoiceType::class, [
                'choices'  => [
                    "À l'agence" => "À l'agence",
                    "En ligne" => "En ligne",
                ]
            ])
            ->add('Etat',ChoiceType::class, [
                'choices'  => [
                    "Non Confirmée" => "Non Confirmée",
                    "Confirmée" => "Confirmée",
                    "En Cours" => "En Cours",
                    "Validée" => "Validée",
                    "Annulée" => "Annulée"
                ]
            ])
            ->add('Status',ChoiceType::class, [
                'choices' => [
                    'En Cours' => 'En Cours',
                    'Payé' => 'Payé',
                    'Annulé' => 'Annulé',
                    'Réfusé' => 'Réfusé',
                ]
            ])
            ->add('Client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => function($nom){
                    return $nom->getName();
                },
            ])
            ->add('Vehicule', EntityType::class, [
                'class' => Vehicule::class,
                'choice_label' => function($nom){
                    return $nom->getModele() ." : ". $nom->getMatricule();
                },
            ])
            ->add('Agence_Depart', EntityType::class, [
                'class' => Agence::class,
                'choice_label' => function($nom){
                    return $nom->getNom();
                },
            ])->add('Agence_Arrive', EntityType::class, [
                'class' => Agence::class,
                'choice_label' => function($nom){
                    return $nom->getNom();
                },
            ])
            ->add('isBabySeat', ChoiceType::class, [
                'choices' => [
                    'Oui' => '1',
                    'Non' => '0'
                ]
            ])
            ->add('isPersonalDriver', ChoiceType::class, [
                'choices' => [
                    'Oui' => '1',
                    'Non' => '0'
                ]
            ])
            ->add('isSecondDriver', ChoiceType::class, [
                'choices' => [
                    'Oui' => '1',
                    'Non' => '0'
                ]
            ])
            ->add('isSTW', ChoiceType::class, [
                'choices' => [
                    'Oui' => '1',
                    'Non' => '0'
                ]
            ])
            ->add('isReservoire', ChoiceType::class, [
                'choices' => [
                    'Oui' => '1',
                    'Non' => '0'
                ]
            ])
            ->add('SecondDriverCIN', TextType::class, [
                'required' => false,
            ])
            ->add('SecondDriverPermis', TextType::class, [
                'required' => false,
            ])
            ->add('SecondDriverDN', DateType::class, [ 
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('SecondDriverDateCIN', DateType::class, [ 
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('SecondDriverDatePermis', DateType::class, [ 
                'widget' => 'single_text',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
