<?php
/*
	Copyright 2009 MONOGRAM Technologies

	This file is part of MONOGRAM EPayment libraries

	MONOGRAM EPayment libraries is free software: you can redistribute it and/or modify
	it under the terms of the GNU Lesser General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	MONOGRAM EPayment libraries is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU Lesser General Public License for more details.

	You should have received a copy of the GNU Lesser General Public License
	along with MONOGRAM EPayment libraries.  If not, see <http://www.gnu.org/licenses/>.
*/
class ISO4217Currency {
    protected $strCode = null;
    protected $numCode = null;
    protected $exponent = null;
    protected $name = null;
    protected $countries = null;

    protected function __construct($key) {
        $this->strCode = ISO4217Currency::$currencies[$key]['strCode'];
        $this->numCode = ISO4217Currency::$currencies[$key]['numCode'];
        $this->exponent = ISO4217Currency::$currencies[$key]['exponent'];
        $this->name = ISO4217Currency::$currencies[$key]['name'];
        $this->countries = ISO4217Currency::$currencies[$key]['countries'];
    }

    public function GetStrCode() {
        return $this->strCode;
    }

    public function GetNumCode() {
        return $this->numCode;
    }

    public function GetExponent() {
        return $this->exponent;
    }

    public function GetName() {
        return $this->name;
    }

    public function GetCountries() {
        return $this->countries;
    }

    public static function GetCurrency($code) {
        $field = null;
        if (is_numeric($code)) {
            $field = 'numCode';
        } else {
            $field = 'strCode';
        }
        foreach (self::$currencies as $key => $currencyDetail) {
            if ($currencyDetail[$field] == $code) {
                return new ISO4217Currency($key);
            }
        }
        return null;
    }

    protected static $currencies = array(
    array('numCode' => 784, 'strCode' => "AED", 'exponent' => 2, 'name' => "United Arab Emirates dirham", 'countries' => "United Arab Emirates"),
    array('numCode' => 971, 'strCode' => "AFN", 'exponent' => 2, 'name' => "Afghani", 'countries' => "Afghanistan"),
    array('numCode' => 8,   'strCode' => "ALL", 'exponent' => 2, 'name' => "Lek", 'countries' => "Albania"),
    array('numCode' => 51,  'strCode' => "AMD", 'exponent' => 2, 'name' => "Armenian dram", 'countries' => "Armenia"),
    array('numCode' => 532, 'strCode' => "ANG", 'exponent' => 2, 'name' => "Netherlands Antillean guilder", 'countries' => "Netherlands Antilles"),
    array('numCode' => 973, 'strCode' => "AOA", 'exponent' => 2, 'name' => "Kwanza", 'countries' => "Angola"),
    array('numCode' => 32,  'strCode' => "ARS", 'exponent' => 2, 'name' => "Argentine peso", 'countries' => "Argentina"),
    array('numCode' => 36,  'strCode' => "AUD", 'exponent' => 2, 'name' => "Australian dollar", 'countries' => "Australia, Australian Antarctic Territory, Christmas Island, Cocos (Keeling) Islands, Heard and McDonald Islands, Kiribati, Nauru, Norfolk Island, Tuvalu"),
    array('numCode' => 533, 'strCode' => "AWG", 'exponent' => 2, 'name' => "Aruban guilder", 'countries' => "Aruba"),
    array('numCode' => 944, 'strCode' => "AZN", 'exponent' => 2, 'name' => "Azerbaijanian manat", 'countries' => "Azerbaijan"),
    array('numCode' => 977, 'strCode' => "BAM", 'exponent' => 2, 'name' => "Convertible marks", 'countries' => "Bosnia and Herzegovina"),
    array('numCode' => 52,  'strCode' => "BBD", 'exponent' => 2, 'name' => "Barbados dollar", 'countries' => "Barbados"),
    array('numCode' => 50,  'strCode' => "BDT", 'exponent' => 2, 'name' => "Bangladeshi taka", 'countries' => "Bangladesh"),
    array('numCode' => 975, 'strCode' => "BGN", 'exponent' => 2, 'name' => "Bulgarian lev", 'countries' => "Bulgaria"),
    array('numCode' => 48,  'strCode' => "BHD", 'exponent' => 3, 'name' => "Bahraini dinar", 'countries' => "Bahrain"),
    array('numCode' => 108, 'strCode' => "BIF", 'exponent' => 0, 'name' => "Burundian franc", 'countries' => "Burundi"),
    array('numCode' => 60,  'strCode' => "BMD", 'exponent' => 2, 'name' => "Bermudian dollar (customarily known as Bermuda dollar)", 'countries' => "Bermuda"),
    array('numCode' => 96,  'strCode' => "BND", 'exponent' => 2, 'name' => "Brunei dollar", 'countries' => "Brunei, Singapore"),
    array('numCode' => 68,  'strCode' => "BOB", 'exponent' => 2, 'name' => "Boliviano", 'countries' => "Bolivia"),
    array('numCode' => 984, 'strCode' => "BOV", 'exponent' => 2, 'name' => "Bolivian Mvdol (funds code)", 'countries' => "Bolivia"),
    array('numCode' => 986, 'strCode' => "BRL", 'exponent' => 2, 'name' => "Brazilian real", 'countries' => "Brazil"),
    array('numCode' => 44,  'strCode' => "BSD", 'exponent' => 2, 'name' => "Bahamian dollar", 'countries' => "Bahamas"),
    array('numCode' => 64,  'strCode' => "BTN", 'exponent' => 2, 'name' => "Ngultrum", 'countries' => "Bhutan"),
    array('numCode' => 72,  'strCode' => "BWP", 'exponent' => 2, 'name' => "Pula", 'countries' => "Botswana"),
    array('numCode' => 974, 'strCode' => "BYR", 'exponent' => 0, 'name' => "Belarussian ruble", 'countries' => "Belarus"),
    array('numCode' => 84,  'strCode' => "BZD", 'exponent' => 2, 'name' => "Belize dollar", 'countries' => "Belize"),
    array('numCode' => 124, 'strCode' => "CAD", 'exponent' => 2, 'name' => "Canadian dollar", 'countries' => "Canada"),
    array('numCode' => 976, 'strCode' => "CDF", 'exponent' => 2, 'name' => "Franc Congolais", 'countries' => "Democratic Republic of Congo"),
    array('numCode' => 947, 'strCode' => "CHE", 'exponent' => 2, 'name' => "WIR euro (complementary currency)", 'countries' => "Switzerland"),
    array('numCode' => 756, 'strCode' => "CHF", 'exponent' => 2, 'name' => "Swiss franc", 'countries' => "Switzerland, Liechtenstein"),
    array('numCode' => 948, 'strCode' => "CHW", 'exponent' => 2, 'name' => "WIR franc (complementary currency)", 'countries' => "Switzerland"),
    array('numCode' => 990, 'strCode' => "CLF", 'exponent' => 0, 'name' => "Unidad de Fomento (funds code)", 'countries' => "Chile"),
    array('numCode' => 152, 'strCode' => "CLP", 'exponent' => 0, 'name' => "Chilean peso", 'countries' => "Chile"),
    array('numCode' => 156, 'strCode' => "CNY", 'exponent' => 2, 'name' => "Renminbi", 'countries' => "Mainland China"),
    array('numCode' => 170, 'strCode' => "COP", 'exponent' => 2, 'name' => "Colombian peso", 'countries' => "Colombia"),
    array('numCode' => 970, 'strCode' => "COU", 'exponent' => 2, 'name' => "Unidad de Valor Real", 'countries' => "Colombia"),
    array('numCode' => 188, 'strCode' => "CRC", 'exponent' => 2, 'name' => "Costa Rican colon", 'countries' => "Costa Rica"),
    array('numCode' => 192, 'strCode' => "CUP", 'exponent' => 2, 'name' => "Cuban peso", 'countries' => "Cuba"),
    array('numCode' => 132, 'strCode' => "CVE", 'exponent' => 2, 'name' => "Cape Verde escudo", 'countries' => "Cape Verde"),
    array('numCode' => 203, 'strCode' => "CZK", 'exponent' => 2, 'name' => "Czech koruna", 'countries' => "Czech Republic"),
    array('numCode' => 262, 'strCode' => "DJF", 'exponent' => 0, 'name' => "Djibouti franc", 'countries' => "Djibouti"),
    array('numCode' => 208, 'strCode' => "DKK", 'exponent' => 2, 'name' => "Danish krone", 'countries' => "Denmark, Faroe Islands, Greenland"),
    array('numCode' => 214, 'strCode' => "DOP", 'exponent' => 2, 'name' => "Dominican peso", 'countries' => "Dominican Republic"),
    array('numCode' => 12,  'strCode' => "DZD", 'exponent' => 2, 'name' => "Algerian dinar", 'countries' => "Algeria"),
    array('numCode' => 233, 'strCode' => "EEK", 'exponent' => 2, 'name' => "Kroon", 'countries' => "Estonia"),
    array('numCode' => 818, 'strCode' => "EGP", 'exponent' => 2, 'name' => "Egyptian pound", 'countries' => "Egypt"),
    array('numCode' => 232, 'strCode' => "ERN", 'exponent' => 2, 'name' => "Nakfa", 'countries' => "Eritrea"),
    array('numCode' => 230, 'strCode' => "ETB", 'exponent' => 2, 'name' => "Ethiopian birr", 'countries' => "Ethiopia"),
    array('numCode' => 978, 'strCode' => "EUR", 'exponent' => 2, 'name' => "Euro", 'countries' => "Some European Union countries; see eurozone"),
    array('numCode' => 242, 'strCode' => "FJD", 'exponent' => 2, 'name' => "Fiji dollar", 'countries' => "Fiji"),
    array('numCode' => 238, 'strCode' => "FKP", 'exponent' => 2, 'name' => "Falkland Islands pound", 'countries' => "Falkland Islands"),
    array('numCode' => 826, 'strCode' => "GBP", 'exponent' => 2, 'name' => "Pound sterling", 'countries' => "United Kingdom, Crown Dependencies (the Isle of Man and the Channel Islands), certain British Overseas Territories (South Georgia and the South Sandwich Islands, British Antarctic Territory and British Indian Ocean Territory)"),
    array('numCode' => 981, 'strCode' => "GEL", 'exponent' => 2, 'name' => "Lari", 'countries' => "Georgia"),
    array('numCode' => 936, 'strCode' => "GHS", 'exponent' => 2, 'name' => "Cedi", 'countries' => "Ghana"),
    array('numCode' => 292, 'strCode' => "GIP", 'exponent' => 2, 'name' => "Gibraltar pound", 'countries' => "Gibraltar"),
    array('numCode' => 270, 'strCode' => "GMD", 'exponent' => 2, 'name' => "Dalasi", 'countries' => "Gambia"),
    array('numCode' => 324, 'strCode' => "GNF", 'exponent' => 0, 'name' => "Guinea franc", 'countries' => "Guinea"),
    array('numCode' => 320, 'strCode' => "GTQ", 'exponent' => 2, 'name' => "Quetzal", 'countries' => "Guatemala"),
    array('numCode' => 328, 'strCode' => "GYD", 'exponent' => 2, 'name' => "Guyana dollar", 'countries' => "Guyana"),
    array('numCode' => 344, 'strCode' => "HKD", 'exponent' => 2, 'name' => "Hong Kong dollar", 'countries' => "Hong Kong Special Administrative Region"),
    array('numCode' => 340, 'strCode' => "HNL", 'exponent' => 2, 'name' => "Lempira", 'countries' => "Honduras"),
    array('numCode' => 191, 'strCode' => "HRK", 'exponent' => 2, 'name' => "Croatian kuna", 'countries' => "Croatia"),
    array('numCode' => 332, 'strCode' => "HTG", 'exponent' => 2, 'name' => "Haiti gourde", 'countries' => "Haiti"),
    array('numCode' => 348, 'strCode' => "HUF", 'exponent' => 2, 'name' => "Forint", 'countries' => "Hungary"),
    array('numCode' => 360, 'strCode' => "IDR", 'exponent' => 2, 'name' => "Rupiah", 'countries' => "Indonesia"),
    array('numCode' => 376, 'strCode' => "ILS", 'exponent' => 2, 'name' => "New Israeli shekel", 'countries' => "Israel"),
    array('numCode' => 356, 'strCode' => "INR", 'exponent' => 2, 'name' => "Indian rupee", 'countries' => "Bhutan, India"),
    array('numCode' => 368, 'strCode' => "IQD", 'exponent' => 3, 'name' => "Iraqi dinar", 'countries' => "Iraq"),
    array('numCode' => 364, 'strCode' => "IRR", 'exponent' => 2, 'name' => "Iranian rial", 'countries' => "Iran"),
    array('numCode' => 352, 'strCode' => "ISK", 'exponent' => 2, 'name' => "Iceland krona", 'countries' => "Iceland"),
    array('numCode' => 388, 'strCode' => "JMD", 'exponent' => 2, 'name' => "Jamaican dollar", 'countries' => "Jamaica"),
    array('numCode' => 400, 'strCode' => "JOD", 'exponent' => 3, 'name' => "Jordanian dinar", 'countries' => "Jordan"),
    array('numCode' => 392, 'strCode' => "JPY", 'exponent' => 0, 'name' => "Japanese yen", 'countries' => "Japan"),
    array('numCode' => 404, 'strCode' => "KES", 'exponent' => 2, 'name' => "Kenyan shilling", 'countries' => "Kenya"),
    array('numCode' => 417, 'strCode' => "KGS", 'exponent' => 2, 'name' => "Som", 'countries' => "Kyrgyzstan"),
    array('numCode' => 116, 'strCode' => "KHR", 'exponent' => 2, 'name' => "Riel", 'countries' => "Cambodia"),
    array('numCode' => 174, 'strCode' => "KMF", 'exponent' => 0, 'name' => "Comoro franc", 'countries' => "Comoros"),
    array('numCode' => 408, 'strCode' => "KPW", 'exponent' => 2, 'name' => "North Korean won", 'countries' => "North Korea"),
    array('numCode' => 410, 'strCode' => "KRW", 'exponent' => 0, 'name' => "South Korean won", 'countries' => "South Korea"),
    array('numCode' => 414, 'strCode' => "KWD", 'exponent' => 3, 'name' => "Kuwaiti dinar", 'countries' => "Kuwait"),
    array('numCode' => 136, 'strCode' => "KYD", 'exponent' => 2, 'name' => "Cayman Islands dollar", 'countries' => "Cayman Islands"),
    array('numCode' => 398, 'strCode' => "KZT", 'exponent' => 2, 'name' => "Tenge", 'countries' => "Kazakhstan"),
    array('numCode' => 418, 'strCode' => "LAK", 'exponent' => 2, 'name' => "Kip", 'countries' => "Laos"),
    array('numCode' => 422, 'strCode' => "LBP", 'exponent' => 2, 'name' => "Lebanese pound", 'countries' => "Lebanon"),
    array('numCode' => 144, 'strCode' => "LKR", 'exponent' => 2, 'name' => "Sri Lanka rupee", 'countries' => "Sri Lanka"),
    array('numCode' => 430, 'strCode' => "LRD", 'exponent' => 2, 'name' => "Liberian dollar", 'countries' => "Liberia"),
    array('numCode' => 426, 'strCode' => "LSL", 'exponent' => 2, 'name' => "Loti", 'countries' => "Lesotho"),
    array('numCode' => 440, 'strCode' => "LTL", 'exponent' => 2, 'name' => "Lithuanian litas", 'countries' => "Lithuania"),
    array('numCode' => 428, 'strCode' => "LVL", 'exponent' => 2, 'name' => "Latvian lats", 'countries' => "Latvia"),
    array('numCode' => 434, 'strCode' => "LYD", 'exponent' => 3, 'name' => "Libyan dinar", 'countries' => "Libya"),
    array('numCode' => 504, 'strCode' => "MAD", 'exponent' => 2, 'name' => "Moroccan dirham", 'countries' => "Morocco, Western Sahara"),
    array('numCode' => 498, 'strCode' => "MDL", 'exponent' => 2, 'name' => "Moldovan leu", 'countries' => "Moldova"),
    array('numCode' => 969, 'strCode' => "MGA", 'exponent' => 0.7, 'name' => "Malagasy ariary", 'countries' => "Madagascar"),
    array('numCode' => 807, 'strCode' => "MKD", 'exponent' => 2, 'name' => "Denar", 'countries' => "Former Yugoslav Republic of Macedonia"),
    array('numCode' => 104, 'strCode' => "MMK", 'exponent' => 2, 'name' => "Kyat", 'countries' => "Myanmar"),
    array('numCode' => 496, 'strCode' => "MNT", 'exponent' => 2, 'name' => "Tugrik", 'countries' => "Mongolia"),
    array('numCode' => 446, 'strCode' => "MOP", 'exponent' => 2, 'name' => "Pataca", 'countries' => "Macau Special Administrative Region"),
    array('numCode' => 478, 'strCode' => "MRO", 'exponent' => 0.7, 'name' => "Ouguiya", 'countries' => "Mauritania"),
    array('numCode' => 480, 'strCode' => "MUR", 'exponent' => 2, 'name' => "Mauritius rupee", 'countries' => "Mauritius"),
    array('numCode' => 462, 'strCode' => "MVR", 'exponent' => 2, 'name' => "Rufiyaa", 'countries' => "Maldives"),
    array('numCode' => 454, 'strCode' => "MWK", 'exponent' => 2, 'name' => "Kwacha", 'countries' => "Malawi"),
    array('numCode' => 484, 'strCode' => "MXN", 'exponent' => 2, 'name' => "Mexican peso", 'countries' => "Mexico"),
    array('numCode' => 979, 'strCode' => "MXV", 'exponent' => 2, 'name' => "Mexican Unidad de Inversion (UDI) (funds code)", 'countries' => "Mexico"),
    array('numCode' => 458, 'strCode' => "MYR", 'exponent' => 2, 'name' => "Malaysian ringgit", 'countries' => "Malaysia"),
    array('numCode' => 943, 'strCode' => "MZN", 'exponent' => 2, 'name' => "Metical", 'countries' => "Mozambique"),
    array('numCode' => 516, 'strCode' => "NAD", 'exponent' => 2, 'name' => "Namibian dollar", 'countries' => "Namibia"),
    array('numCode' => 566, 'strCode' => "NGN", 'exponent' => 2, 'name' => "Naira", 'countries' => "Nigeria"),
    array('numCode' => 558, 'strCode' => "NIO", 'exponent' => 2, 'name' => "Cordoba oro", 'countries' => "Nicaragua"),
    array('numCode' => 578, 'strCode' => "NOK", 'exponent' => 2, 'name' => "Norwegian krone", 'countries' => "Norway"),
    array('numCode' => 524, 'strCode' => "NPR", 'exponent' => 2, 'name' => "Nepalese rupee", 'countries' => "Nepal"),
    array('numCode' => 554, 'strCode' => "NZD", 'exponent' => 2, 'name' => "New Zealand dollar", 'countries' => "Cook Islands, New Zealand, Niue, Pitcairn, Tokelau"),
    array('numCode' => 512, 'strCode' => "OMR", 'exponent' => 3, 'name' => "Rial Omani", 'countries' => "Oman"),
    array('numCode' => 590, 'strCode' => "PAB", 'exponent' => 2, 'name' => "Balboa", 'countries' => "Panama"),
    array('numCode' => 604, 'strCode' => "PEN", 'exponent' => 2, 'name' => "Nuevo sol", 'countries' => "Peru"),
    array('numCode' => 598, 'strCode' => "PGK", 'exponent' => 2, 'name' => "Kina", 'countries' => "Papua New Guinea"),
    array('numCode' => 608, 'strCode' => "PHP", 'exponent' => 2, 'name' => "Philippine peso", 'countries' => "Philippines"),
    array('numCode' => 586, 'strCode' => "PKR", 'exponent' => 2, 'name' => "Pakistan rupee", 'countries' => "Pakistan"),
    array('numCode' => 985, 'strCode' => "PLN", 'exponent' => 2, 'name' => "Zloty", 'countries' => "Poland"),
    array('numCode' => 600, 'strCode' => "PYG", 'exponent' => 0, 'name' => "Guarani", 'countries' => "Paraguay"),
    array('numCode' => 634, 'strCode' => "QAR", 'exponent' => 2, 'name' => "Qatari rial", 'countries' => "Qatar"),
    array('numCode' => 946, 'strCode' => "RON", 'exponent' => 2, 'name' => "Romanian new leu", 'countries' => "Romania"),
    array('numCode' => 941, 'strCode' => "RSD", 'exponent' => 2, 'name' => "Serbian dinar", 'countries' => "Serbia"),
    array('numCode' => 643, 'strCode' => "RUB", 'exponent' => 2, 'name' => "Russian ruble", 'countries' => "Russia, Abkhazia, South Ossetia"),
    array('numCode' => 646, 'strCode' => "RWF", 'exponent' => 0, 'name' => "Rwanda franc", 'countries' => "Rwanda"),
    array('numCode' => 682, 'strCode' => "SAR", 'exponent' => 2, 'name' => "Saudi riyal", 'countries' => "Saudi Arabia"),
    array('numCode' => 90,  'strCode' => "SBD", 'exponent' => 2, 'name' => "Solomon Islands dollar", 'countries' => "Solomon Islands"),
    array('numCode' => 690, 'strCode' => "SCR", 'exponent' => 2, 'name' => "Seychelles rupee", 'countries' => "Seychelles"),
    array('numCode' => 938, 'strCode' => "SDG", 'exponent' => 2, 'name' => "Sudanese pound", 'countries' => "Sudan"),
    array('numCode' => 752, 'strCode' => "SEK", 'exponent' => 2, 'name' => "Swedish krona", 'countries' => "Sweden"),
    array('numCode' => 702, 'strCode' => "SGD", 'exponent' => 2, 'name' => "Singapore dollar", 'countries' => "Singapore, Brunei"),
    array('numCode' => 654, 'strCode' => "SHP", 'exponent' => 2, 'name' => "Saint Helena pound", 'countries' => "Saint Helena"),
    array('numCode' => 703, 'strCode' => "SKK", 'exponent' => 2, 'name' => "Slovak koruna", 'countries' => "Slovakia"),
    array('numCode' => 694, 'strCode' => "SLL", 'exponent' => 2, 'name' => "Leone", 'countries' => "Sierra Leone"),
    array('numCode' => 706, 'strCode' => "SOS", 'exponent' => 2, 'name' => "Somali shilling", 'countries' => "Somalia"),
    array('numCode' => 968, 'strCode' => "SRD", 'exponent' => 2, 'name' => "Surinam dollar", 'countries' => "Suriname"),
    array('numCode' => 678, 'strCode' => "STD", 'exponent' => 2, 'name' => "Dobra", 'countries' => "S?o Tom� and Pr�ncipe"),
    array('numCode' => 760, 'strCode' => "SYP", 'exponent' => 2, 'name' => "Syrian pound", 'countries' => "Syria"),
    array('numCode' => 748, 'strCode' => "SZL", 'exponent' => 2, 'name' => "Lilangeni", 'countries' => "Swaziland"),
    array('numCode' => 764, 'strCode' => "THB", 'exponent' => 2, 'name' => "Baht", 'countries' => "Thailand"),
    array('numCode' => 972, 'strCode' => "TJS", 'exponent' => 2, 'name' => "Somoni", 'countries' => "Tajikistan"),
    array('numCode' => 795, 'strCode' => "TMM", 'exponent' => 2, 'name' => "Manat", 'countries' => "Turkmenistan"),
    array('numCode' => 788, 'strCode' => "TND", 'exponent' => 3, 'name' => "Tunisian dinar", 'countries' => "Tunisia"),
    array('numCode' => 776, 'strCode' => "TOP", 'exponent' => 2, 'name' => "Pa'anga", 'countries' => "Tonga"),
    array('numCode' => 949, 'strCode' => "TRY", 'exponent' => 2, 'name' => "New Turkish lira", 'countries' => "Turkey"),
    array('numCode' => 780, 'strCode' => "TTD", 'exponent' => 2, 'name' => "Trinidad and Tobago dollar", 'countries' => "Trinidad and Tobago"),
    array('numCode' => 901, 'strCode' => "TWD", 'exponent' => 2, 'name' => "New Taiwan dollar", 'countries' => "Taiwan and other islands that are under the effective control of the Republic of China (ROC)"),
    array('numCode' => 834, 'strCode' => "TZS", 'exponent' => 2, 'name' => "Tanzanian shilling", 'countries' => "Tanzania"),
    array('numCode' => 980, 'strCode' => "UAH", 'exponent' => 2, 'name' => "Hryvnia", 'countries' => "Ukraine"),
    array('numCode' => 800, 'strCode' => "UGX", 'exponent' => 2, 'name' => "Uganda shilling", 'countries' => "Uganda"),
    array('numCode' => 840, 'strCode' => "USD", 'exponent' => 2, 'name' => "US dollar", 'countries' => "American Samoa, British Indian Ocean Territory, Ecuador, El Salvador, Guam, Haiti, Marshall Islands, Micronesia, Northern Mariana Islands, Palau, Panama, Puerto Rico, Timor-Leste, Turks and Caicos Islands, United States, Virgin Islands"),
    array('numCode' => 997, 'strCode' => "USN", 'exponent' => 2, 'name' => "United States dollar (next day) (funds code)", 'countries' => "United States"),
    array('numCode' => 998, 'strCode' => "USS", 'exponent' => 2, 'name' => "United States dollar (same day) (funds code) (one source claims it is no longer used, but it is still on the ISO 4217-MA list)", 'countries' => "United States"),
    array('numCode' => 858, 'strCode' => "UYU", 'exponent' => 2, 'name' => "Peso Uruguayo", 'countries' => "Uruguay"),
    array('numCode' => 860, 'strCode' => "UZS", 'exponent' => 2, 'name' => "Uzbekistan som", 'countries' => "Uzbekistan"),
    array('numCode' => 937, 'strCode' => "VEF", 'exponent' => 2, 'name' => "Venezuelan bol�var fuerte", 'countries' => "Venezuela"),
    array('numCode' => 704, 'strCode' => "VND", 'exponent' => 2, 'name' => "Vietnamese �?ng", 'countries' => "Vietnam"),
    array('numCode' => 548, 'strCode' => "VUV", 'exponent' => 0, 'name' => "Vatu", 'countries' => "Vanuatu"),
    array('numCode' => 882, 'strCode' => "WST", 'exponent' => 2, 'name' => "Samoan tala", 'countries' => "Samoa"),
    array('numCode' => 950, 'strCode' => "XAF", 'exponent' => 0, 'name' => "CFA franc BEAC", 'countries' => "Cameroon, Central African Republic, Congo, Chad, Equatorial Guinea, Gabon"),
    array('numCode' => 961, 'strCode' => "XAG", 'exponent' => null, 'name' => "Silver (one troy ounce)", 'countries' => ""),
    array('numCode' => 959, 'strCode' => "XAU", 'exponent' => null, 'name' => "Gold (one troy ounce)", 'countries' => ""),
    array('numCode' => 955, 'strCode' => "XBA", 'exponent' => null, 'name' => "European Composite Unit (EURCO) (bond market unit)", 'countries' => ""),
    array('numCode' => 956, 'strCode' => "XBB", 'exponent' => null, 'name' => "European Monetary Unit (E.M.U.-6) (bond market unit)", 'countries' => ""),
    array('numCode' => 957, 'strCode' => "XBC", 'exponent' => null, 'name' => "European Unit of Account 9 (E.U.A.-9) (bond market unit)", 'countries' => ""),
    array('numCode' => 958, 'strCode' => "XBD", 'exponent' => null, 'name' => "European Unit of Account 17 (E.U.A.-17) (bond market unit)", 'countries' => ""),
    array('numCode' => 951, 'strCode' => "XCD", 'exponent' => 2, 'name' => "East Caribbean dollar", 'countries' => "Anguilla, Antigua and Barbuda, Dominica, Grenada, Montserrat, Saint Kitts and Nevis, Saint Lucia, Saint Vincent and the Grenadines"),
    array('numCode' => 960, 'strCode' => "XDR", 'exponent' => null, 'name' => "Special Drawing Rights", 'countries' => "International Monetary Fund"),
    //array('numCode' => Nil, 'strCode' => "XFU", 'exponent' => ., 'name' => "UIC franc (special settlement currency)", 'countries' => "International Union of Railways"),
    array('numCode' => 952, 'strCode' => "XOF", 'exponent' => 0, 'name' => "CFA Franc BCEAO", 'countries' => "Benin, Burkina Faso, C�te d'Ivoire, Guinea-Bissau, Mali, Niger, Senegal, Togo"),
    array('numCode' => 964, 'strCode' => "XPD", 'exponent' => null, 'name' => "Palladium (one troy ounce)", 'countries' => ""),
    array('numCode' => 953, 'strCode' => "XPF", 'exponent' => 0, 'name' => "CFP franc", 'countries' => "French Polynesia, New Caledonia, Wallis and Futuna"),
    array('numCode' => 962, 'strCode' => "XPT", 'exponent' => null, 'name' => "Platinum (one troy ounce)", 'countries' => ""),
    array('numCode' => 963, 'strCode' => "XTS", 'exponent' => null, 'name' => "Code reserved for testing purposes", 'countries' => ""),
    //array('numCode' => 999, 'strCode' => "XXX", 'exponent' => ., 'name' => "No currency", 'countries' => ""),
    array('numCode' => 886, 'strCode' => "YER", 'exponent' => 2, 'name' => "Yemeni rial", 'countries' => "Yemen"),
    array('numCode' => 710, 'strCode' => "ZAR", 'exponent' => 2, 'name' => "South African rand", 'countries' => "South Africa"),
    array('numCode' => 894, 'strCode' => "ZMK", 'exponent' => 2, 'name' => "Kwacha", 'countries' => "Zambia"),
    array('numCode' => 716, 'strCode' => "ZWD", 'exponent' => 2, 'name' => "Zimbabwe dollar", 'countries' => "Zimbabwe"),
    );
}