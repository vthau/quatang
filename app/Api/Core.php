<?php

namespace App\Api;

use App\Model\UserSupplier;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;

use \DateTime;
use \Artisan;
use \Session;

use App\Api\FE;
use App\User;

use App\Model\Photo;
use App\Model\File;
use App\Model\Setting;
use App\Model\Log;
use App\Model\ProductCategory;
use App\Model\Product;
use App\Model\ProductBrand;
use App\Model\News;
use App\Model\Event;
use App\Model\UserCart;
use App\Model\Notification;

class Core
{
    public function testMode()
    {
        return true;
    }

    public function getViewer()
    {
        return Auth::check() ? Auth::user() : NULL;
    }

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
    }

    public function getKey($value)
    {
        $key = "";

        switch ($value) {
            case 'tinymce':
                $key = "uia8on2oc5ua75809jee46xcvbpkgb46y5e3bzw1hm6et9wc";
                break;
        }

        return $key;
    }

    public function timeToString($datetime, $full = false)
    {
        $now = new DateTime();
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'năm',
            'm' => 'tháng',
            'w' => 'tuần',
            'd' => 'ngày',
            'h' => 'giờ',
            'i' => 'phút',
            's' => 'giây',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v; // . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' trước' : 'vừa cập nhật'; //' ago' : 'just now';
    }

    public function stripVN($str)
    {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);

        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        return $str;
    }

    public function generateHref($item, $values = array())
    {
        $id = 0;
        $href = $values['name'];
        $href = strtolower($href);
        $href = str_replace("-", " ", $href);
        $href = preg_replace('/\s\s+/', ' ', $href);
        $href = str_replace(" ", "-", trim($href));
        $count = 0;

        switch ($item) {
            case 'user':
                $item = User::find((int)$values['id']);
                if ($item && $item->id) {
                    $id = $item->id;
                }

                do {
                    $temp = $href;
                    if ($count) {
                        $temp = $temp . "-" . $count;
                    }

                    $select = User::where('deleted', 0)
                        ->where("href", $temp);
                    if ($id) {
                        $select->where("id", "<>", $id);
                    }
                    $row = $select->first();

                    if (!$row) {
                        $href = $temp;
                        break;
                    }

                    $count++;
                } while (1);

                break;

            case 'product_category':
                $item = ProductCategory::find((int)$values['id']);
                if ($item && $item->id) {
                    $id = $item->id;
                }

                do {
                    $temp = $href;
                    if ($count) {
                        $temp = $temp . "-" . $count;
                    }

                    $select = ProductCategory::where('deleted', 0)
                        ->where("href", $temp);
                    if ($id) {
                        $select->where("id", "<>", $id);
                    }
                    $row = $select->first();

                    if (!$row) {
                        $href = $temp;
                        break;
                    }

                    $count++;
                } while (1);

                break;

            case 'product_brand':
                $item = ProductBrand::find((int)$values['id']);
                if ($item && $item->id) {
                    $id = $item->id;
                }

                do {
                    $temp = $href;
                    if ($count) {
                        $temp = $temp . "-" . $count;
                    }

                    $select = ProductBrand::where('deleted', 0)
                        ->where("href", $temp);
                    if ($id) {
                        $select->where("id", "<>", $id);
                    }
                    $row = $select->first();

                    if (!$row) {
                        $href = $temp;
                        break;
                    }

                    $count++;
                } while (1);

                break;

            case 'product':
                $item = Product::find((int)$values['id']);
                if ($item && $item->id) {
                    $id = $item->id;
                }

                do {
                    $temp = $href;
                    if ($count) {
                        $temp = $temp . "-" . $count;
                    }

                    $select = Product::where('deleted', 0)
                        ->where("href", $temp);
                    if ($id) {
                        $select->where("id", "<>", $id);
                    }
                    $row = $select->first();

                    if (!$row) {
                        $href = $temp;
                        break;
                    }

                    $count++;
                } while (1);

                break;

            case 'news':
                $item = News::find((int)$values['id']);
                if ($item && $item->id) {
                    $id = $item->id;
                }

                do {
                    $temp = $href;
                    if ($count) {
                        $temp = $temp . "-" . $count;
                    }

                    $select = News::where('deleted', 0)
                        ->where("href", $temp);
                    if ($id) {
                        $select->where("id", "<>", $id);
                    }
                    $row = $select->first();

                    if (!$row) {
                        $href = $temp;
                        break;
                    }

                    $count++;
                } while (1);

                break;

            case 'event':
                $item = Event::find((int)$values['id']);
                if ($item && $item->id) {
                    $id = $item->id;
                }

                do {
                    $temp = $href;
                    if ($count) {
                        $temp = $temp . "-" . $count;
                    }

                    $select = Event::where('deleted', 0)
                        ->where("href", $temp);
                    if ($id) {
                        $select->where("id", "<>", $id);
                    }
                    $row = $select->first();

                    if (!$row) {
                        $href = $temp;
                        break;
                    }

                    $count++;
                } while (1);

                break;

            case 'cart':
                $item = UserCart::find((int)$values['id']);
                if ($item && $item->id) {
                    $id = $item->id;
                }

                do {
                    $temp = $href;
                    if ($count) {
                        $temp = $temp . "-" . $count;
                    }

                    $select = UserCart::where('deleted', 0)
                        ->where("href", $temp);
                    if ($id) {
                        $select->where("id", "<>", $id);
                    }
                    $row = $select->first();

                    if (!$row) {
                        $href = $temp;
                        break;
                    }

                    $count++;
                } while (1);

                break;
        }
        return $href;
    }

    public function platformSlashes($path)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
            $path = str_replace('/', '\\', $path);
        }
        return $path;
    }

    public function rotateImage($path)
    {
        $path = $this->platformSlashes($path);
        if (is_file($path)) {
            $image = \Image::make($path);
            // perform orientation using intervention
            $image->orientate();
            // save image
            $image->save();
        }
    }

    public function listCountries()
    {
        return array(
            "AF" => "Afghanistan",
            "AL" => "Albania",
            "DZ" => "Algeria",
            "AS" => "American Samoa",
            "AD" => "Andorra",
            "AO" => "Angola",
            "AI" => "Anguilla",
            "AQ" => "Antarctica",
            "AG" => "Antigua and Barbuda",
            "AR" => "Argentina",
            "AM" => "Armenia",
            "AW" => "Aruba",
            "AU" => "Australia",
            "AT" => "Austria",
            "AZ" => "Azerbaijan",
            "BS" => "Bahamas",
            "BH" => "Bahrain",
            "BD" => "Bangladesh",
            "BB" => "Barbados",
            "BY" => "Belarus",
            "BE" => "Belgium",
            "BZ" => "Belize",
            "BJ" => "Benin",
            "BM" => "Bermuda",
            "BT" => "Bhutan",
            "BO" => "Bolivia",
            "BA" => "Bosnia and Herzegovina",
            "BW" => "Botswana",
            "BV" => "Bouvet Island",
            "BR" => "Brazil",
            "IO" => "British Indian Ocean Territory",
            "BN" => "Brunei Darussalam",
            "BG" => "Bulgaria",
            "BF" => "Burkina Faso",
            "BI" => "Burundi",
            "KH" => "Cambodia",
            "CM" => "Cameroon",
            "CA" => "Canada",
            "CV" => "Cape Verde",
            "KY" => "Cayman Islands",
            "CF" => "Central African Republic",
            "TD" => "Chad",
            "CL" => "Chile",
            "CN" => "China",
            "CX" => "Christmas Island",
            "CC" => "Cocos (Keeling) Islands",
            "CO" => "Colombia",
            "KM" => "Comoros",
            "CG" => "Congo",
            "CD" => "Congo, the Democratic Republic of the",
            "CK" => "Cook Islands",
            "CR" => "Costa Rica",
            "CI" => "Cote D'Ivoire",
            "HR" => "Croatia",
            "CU" => "Cuba",
            "CY" => "Cyprus",
            "CZ" => "Czech Republic",
            "DK" => "Đan Mạch",
            "DJ" => "Djibouti",
            "DM" => "Dominica",
            "DO" => "Dominican Republic",
            "EC" => "Ecuador",
            "EG" => "Egypt",
            "SV" => "El Salvador",
            "GQ" => "Equatorial Guinea",
            "ER" => "Eritrea",
            "EE" => "Estonia",
            "ET" => "Ethiopia",
            "FK" => "Falkland Islands (Malvinas)",
            "FO" => "Faroe Islands",
            "FJ" => "Fiji",
            "FI" => "Finland",
            "FR" => "France",
            "GF" => "French Guiana",
            "PF" => "French Polynesia",
            "TF" => "French Southern Territories",
            "GA" => "Gabon",
            "GM" => "Gambia",
            "GE" => "Georgia",
            "DE" => "Germany",
            "GH" => "Ghana",
            "GI" => "Gibraltar",
            "GR" => "Greece",
            "GL" => "Greenland",
            "GD" => "Grenada",
            "GP" => "Guadeloupe",
            "GU" => "Guam",
            "GT" => "Guatemala",
            "GN" => "Guinea",
            "GW" => "Guinea-Bissau",
            "GY" => "Guyana",
            "HT" => "Haiti",
            "HM" => "Heard Island and Mcdonald Islands",
            "VA" => "Holy See (Vatican City State)",
            "HN" => "Honduras",
            "HK" => "Hong Kong",
            "HU" => "Hungary",
            "IS" => "Iceland",
            "IN" => "India",
            "ID" => "Indonesia",
            "IR" => "Iran, Islamic Republic of",
            "IQ" => "Iraq",
            "IE" => "Ireland",
            "IL" => "Israel",
            "IT" => "Italy",
            "JM" => "Jamaica",
            "JP" => "Japan",
            "JO" => "Jordan",
            "KZ" => "Kazakhstan",
            "KE" => "Kenya",
            "KI" => "Kiribati",
            "KP" => "Korea, Democratic People's Republic of",
            "KR" => "Korea, Republic of",
            "KW" => "Kuwait",
            "KG" => "Kyrgyzstan",
            "LA" => "Lao People's Democratic Republic",
            "LV" => "Latvia",
            "LB" => "Lebanon",
            "LS" => "Lesotho",
            "LR" => "Liberia",
            "LY" => "Libyan Arab Jamahiriya",
            "LI" => "Liechtenstein",
            "LT" => "Lithuania",
            "LU" => "Luxembourg",
            "MO" => "Macao",
            "MK" => "Macedonia, the Former Yugoslav Republic of",
            "MG" => "Madagascar",
            "MW" => "Malawi",
            "MY" => "Malaysia",
            "MV" => "Maldives",
            "ML" => "Mali",
            "MT" => "Malta",
            "MH" => "Marshall Islands",
            "MQ" => "Martinique",
            "MR" => "Mauritania",
            "MU" => "Mauritius",
            "YT" => "Mayotte",
            "MX" => "Mexico",
            "FM" => "Micronesia, Federated States of",
            "MD" => "Moldova, Republic of",
            "MC" => "Monaco",
            "MN" => "Mongolia",
            "MS" => "Montserrat",
            "MA" => "Morocco",
            "MZ" => "Mozambique",
            "MM" => "Myanmar",
            "NA" => "Namibia",
            "NR" => "Nauru",
            "NP" => "Nepal",
            "NL" => "Netherlands",
            "AN" => "Netherlands Antilles",
            "NC" => "New Caledonia",
            "NZ" => "New Zealand",
            "NI" => "Nicaragua",
            "NE" => "Niger",
            "NG" => "Nigeria",
            "NU" => "Niue",
            "NF" => "Norfolk Island",
            "MP" => "Northern Mariana Islands",
            "NO" => "Norway",
            "OM" => "Oman",
            "PK" => "Pakistan",
            "PW" => "Palau",
            "PS" => "Palestinian Territory, Occupied",
            "PA" => "Panama",
            "PG" => "Papua New Guinea",
            "PY" => "Paraguay",
            "PE" => "Peru",
            "PH" => "Philippines",
            "PN" => "Pitcairn",
            "PL" => "Poland",
            "PT" => "Portugal",
            "PR" => "Puerto Rico",
            "QA" => "Qatar",
            "RE" => "Reunion",
            "RO" => "Romania",
            "RU" => "Russian Federation",
            "RW" => "Rwanda",
            "SH" => "Saint Helena",
            "KN" => "Saint Kitts and Nevis",
            "LC" => "Saint Lucia",
            "PM" => "Saint Pierre and Miquelon",
            "VC" => "Saint Vincent and the Grenadines",
            "WS" => "Samoa",
            "SM" => "San Marino",
            "ST" => "Sao Tome and Principe",
            "SA" => "Saudi Arabia",
            "SN" => "Senegal",
            "CS" => "Serbia and Montenegro",
            "SC" => "Seychelles",
            "SL" => "Sierra Leone",
            "SG" => "Singapore",
            "SK" => "Slovakia",
            "SI" => "Slovenia",
            "SB" => "Solomon Islands",
            "SO" => "Somalia",
            "ZA" => "South Africa",
            "GS" => "South Georgia and the South Sandwich Islands",
            "ES" => "Spain",
            "LK" => "Sri Lanka",
            "SD" => "Sudan",
            "SR" => "Suriname",
            "SJ" => "Svalbard and Jan Mayen",
            "SZ" => "Swaziland",
            "SE" => "Sweden",
            "CH" => "Switzerland",
            "SY" => "Syrian Arab Republic",
            "TW" => "Taiwan, Province of China",
            "TJ" => "Tajikistan",
            "TZ" => "Tanzania, United Republic of",
            "TH" => "Thailand",
            "TL" => "Timor-Leste",
            "TG" => "Togo",
            "TK" => "Tokelau",
            "TO" => "Tonga",
            "TT" => "Trinidad and Tobago",
            "TN" => "Tunisia",
            "TR" => "Turkey",
            "TM" => "Turkmenistan",
            "TC" => "Turks and Caicos Islands",
            "TV" => "Tuvalu",
            "UG" => "Uganda",
            "UA" => "Ukraine",
            "AE" => "United Arab Emirates",
            "GB" => "United Kingdom",
            "US" => "United States",
            "UM" => "United States Minor Outlying Islands",
            "UY" => "Uruguay",
            "UZ" => "Uzbekistan",
            "VU" => "Vanuatu",
            "VE" => "Venezuela",
            "VN" => "Viet Nam",
            "VG" => "Virgin Islands, British",
            "VI" => "Virgin Islands, U.s.",
            "WF" => "Wallis and Futuna",
            "EH" => "Western Sahara",
            "YE" => "Yemen",
            "ZM" => "Zambia",
            "ZW" => "Zimbabwe"
        );
    }

    public function textShortcut($value)
    {
        $words = explode(" ", $value);
        $acronym = "";

        foreach ($words as $w) {
            $acronym .= strtoupper($this->stripVN(mb_substr($w, 0, 1)));
        }

        return $acronym;
    }

    public function getPageTitle()
    {
        return "GECKOSO DEMO";
    }

    public function getSetting($setting, $params = [])
    {
        $row = Setting::where('title', $setting)->first();
        $value = ($row) ? $row->value : "";

        if (empty($value)) {
            if ($setting == "site_title") {
                $value = "GECKOSO DEMO";
            } elseif ($setting == "site_logo") {

                $logo = Photo::where('type', 'site_logo')->where('parent_id', 0)->first();
                if ($logo) {
                    $thumb = "";
                    if (isset($params['thumb'])) {
                        $thumb = $params['thumb'];
                    }
                    $value = $logo->getPhoto($thumb);
                } else {
                    $value = url('public/images/logo/favicon.ico');
                }
            } elseif ($setting == "site_logo_ngang") {

                $logo = Photo::where('type', 'site_logo_ngang')->where('parent_id', 0)->first();
                if ($logo) {
                    $thumb = "";
                    if (isset($params['thumb'])) {
                        $thumb = $params['thumb'];
                    }
                    $value = $logo->getPhoto($thumb);
                } else {
                    $value = url('public/images/logo/favicon.ico');
                }
            } elseif ($setting == "site_bg_doctor") {

                $logo = Photo::where('type', 'site_bg_doctor')->where('parent_id', 0)->first();
                if ($logo) {
                    $thumb = "";
                    if (isset($params['thumb'])) {
                        $thumb = $params['thumb'];
                    }
                    $value = $logo->getPhoto($thumb);
                }
            } elseif (preg_match("/text_/i", $setting)) {
                //

            }
        }

        return $value;
    }

    public function uploadLogo($name, $path)
    {
        $rows = Photo::where('type', 'site_logo')->get();
        if (count($rows)) {
            foreach ($rows as $row) {
                $diskPath = public_path('/' . $row->path);
                $diskPath = $this->platformSlashes($diskPath);
                if (is_file($diskPath) && !empty($row->thumb)) {
                    unlink($diskPath);
                }

                $row->delete();
            }
        }

        $row = Photo::create([
            'item_type' => 'site_logo',
            'item_id' => 0,
            'type' => 'site_logo',
            'name' => $name,
            'path' => $path,
        ]);
        $row->createThumb();
    }

    public function uploadLogo2($name, $path)
    {
        $rows = Photo::where('type', 'site_logo_ngang')->get();
        if (count($rows)) {
            foreach ($rows as $row) {
                $diskPath = public_path('/' . $row->path);
                $diskPath = $this->platformSlashes($diskPath);
                if (is_file($diskPath) && !empty($row->thumb)) {
                    unlink($diskPath);
                }

                $row->delete();
            }
        }

        $row = Photo::create([
            'item_type' => 'site_logo_ngang',
            'item_id' => 0,
            'type' => 'site_logo_ngang',
            'name' => $name,
            'path' => $path,
        ]);
        $row->createThumb();
    }

    public function uploadLogo3($name, $path)
    {
        $rows = Photo::where('type', 'site_bg_doctor')->get();
        if (count($rows)) {
            foreach ($rows as $row) {
                $diskPath = public_path('/' . $row->path);
                $diskPath = $this->platformSlashes($diskPath);
                if (is_file($diskPath) && !empty($row->thumb)) {
                    unlink($diskPath);
                }

                $row->delete();
            }
        }

        $row = Photo::create([
            'item_type' => 'site_bg_doctor',
            'item_id' => 0,
            'type' => 'site_bg_doctor',
            'name' => $name,
            'path' => $path,
        ]);
        $row->createThumb();
    }

    public function updateSettings($items = [])
    {
        if (count($items)) {
            foreach ($items as $k => $v) {
                $this->updateSetting($k, $v);
            }
        }
    }

    public function updateSetting($key, $value)
    {
        $row = Setting::where('title', $key)->first();
        if ($row) {
            $row->update([
                'value' => $value
            ]);
        } else {
            Setting::create([
                'title' => $key,
                'value' => $value
            ]);
        }
    }

    public function addLog($values)
    {
        if (count($values)) {
            Log::create($values);
        }
    }

    public function getMaxSize()
    {
        return 5242880;
    }

    public function getMaxSizeText()
    {
        return "5Mb";
    }

    public function getBrands($params = [])
    {
        $select = ProductBrand::where('deleted', 0)
            ->orderByRaw("TRIM(LOWER(title))");

        if (isset($params['active'])) {
            $select->where('active', 1);
        }

        return $select->get();
    }

    public function getCates()
    {
        $select = ProductCategory::where('deleted', 0)
            ->where('level', 1)
            ->where('is_menu', 1)
            ->orderByRaw("TRIM(LOWER(title))")
            ->limit(5);
        return $select->get();
    }

    public function getClients()
    {
        $select = User::where('deleted', 0)
            ->where('level_id', 4);
        return $select->get();
    }

    public function getItem($itemType, $itemId)
    {
        $item = null;
        switch ($itemType) {
            case 'event':
                $item = Event::find((int)$itemId);
                break;

            case 'news':
                $item = News::find((int)$itemId);
                break;

            case 'cart':
                $item = UserCart::find((int)$itemId);
                break;

            case 'product':
                $item = Product::find((int)$itemId);
                break;

            case 'user':
                $item = User::find((int)$itemId);
                break;

            case 'user_supplier':
                $item = UserSupplier::find((int)$itemId);
                break;

        }
        return $item;
    }

    public function addNotification($userId, $type, $params = [])
    {
        //contact_new, product_sale_run, client_new, cart_new

        //insert
        $row = Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'subject_type' => isset($params['subject_type']) ? $params['subject_type'] : NULL,
            'subject_id' => isset($params['subject_id']) ? $params['subject_id'] : NULL,
            'object_type' => isset($params['object_type']) ? $params['object_type'] : NULL,
            'object_id' => isset($params['object_id']) ? $params['object_id'] : NULL,
        ]);
        if (count($params)) {
            $row->update([
                'params' => json_encode($params)
            ]);
        }
        return true;
    }

    public function notifyAdmin($type, $params = [])
    {
        $admins = User::where('deleted', 0)
            ->where("level_id", "=", 1)
            ->get();

        if (count($admins)) {
            foreach ($admins as $admin) {
                $this->addNotification($admin->id, $type, $params);
            }
        }
    }

    public function notifyAllStaffs($type, $params = [])
    {
        $staffs = User::where('deleted', 0)
            ->where("level_id", "=", 1)
            ->orWhere("level_id", "=", 2)
            ->orWhere("level_id", "=", 3)
            ->get();

        if (count($staffs)) {
            foreach ($staffs as $staff) {
                $this->addNotification($staff->id, $type, $params);
            }
        }
    }

    public function clearNotifications($itemType, $itemId)
    {
        Notification::where('subject_type', $itemType)
            ->where('subject_id', $itemId)
            ->delete();

        Notification::where('object_type', $itemType)
            ->where('object_id', $itemId)
            ->delete();

        if ($itemType == 'user') {
            Notification::where('user_id', $itemId)
                ->delete();
        }
    }

    public function cleanStr($str)
    {
        return trim(strip_tags($str));
    }

    public function parseToInt($value)
    {
        $value = str_replace(',', '', $value);
        $value = str_replace('.', '', $value);

        return (int)$value;
    }

    public function parseToFloat($value)
    {
        $value = str_replace(',', '', $value);

        return (float)$value;
    }

    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function numberToExcel($number)
    {
        return number_format($number, 0, '', ',');
    }

    //refer
    public function getRefers()
    {
        $select = User::where('level_id', 4)
            ->where('deleted', 0)
            ->where('hop_tac', 1)
            ->where(function($q) {
                $q->where('chung_chi_truc_tiep', '>', 0)
                    ->orWhere('chung_chi_gian_tiep', '>', 0);
            })
            ->orderByRaw('TRIM(LOWER(name))');
        return $select->get();
    }

    public function getTestDetail($testId, $queId)
    {
        $select = TestDetail::where('test_id', $testId)
            ->where('test_question_id', $queId);
        return $select->first();
    }

    public function canMakeTest($type)
    {
        $settingTTQuestions = (int)$this->getSetting('truc_tiep_total');
        $settingGTQuestions = (int)$this->getSetting('gian_tiep_total');

        $questions = TestQuestion::where('deleted', 0)
            ->where('type', $type)
            ->get();

        if ($type == 'truc_tiep' && count($questions) >= $settingTTQuestions) {
            return true;
        }

        if ($type == 'gian_tiep' && count($questions) >= $settingGTQuestions) {
            return true;
        }

        return false;
    }

    public function timHHHT($userId, $cartId)
    {
        $select = UserCart::where('ht_refer_id', $userId)
            ->where('id', $cartId);
        return $select->first();
    }

    public function getAdmins()
    {
        $select = User::where('level_id', 1)
            ->where('deleted', 0);

        return $select->get();
    }

    public function ghiNhoThuongDoanhSo()
    {
        $keyword = 'TDS_' . (int)date('m') . '_' . (int)date('Y');

        $value = json_encode([
            'cap_1_doanh_so' => $this->getSetting('doanh_so_gold'),
            'cap_1_phan_tram' => $this->getSetting('percent_gold'),
            'cap_2_doanh_so' => $this->getSetting('doanh_so_diamond'),
            'cap_2_phan_tram' => $this->getSetting('percent_diamond'),
            'cap_3_doanh_so' => $this->getSetting('doanh_so_platinum'),
            'cap_3_phan_tram' => $this->getSetting('percent_platinum'),
        ]);

        $row = Setting::where('title', $keyword)
            ->first();
        if ($row) {
            $row->update([
                'value' => $value,
            ]);
        } else {
            Setting::create([
                'title' => $keyword,
                'value' => $value,
            ]);
        }

        //ktra voi setting moi
        $users = User::where('deleted', 0)
            ->where('ref_code', '<>', NULL)
            ->get();
        if (count($users)) {
            foreach ($users as $user) {
                $user->createCommission((int)date('m'), (int)date('Y'));
            }
        }

    }

    public function numberCurrency($number)
    {
        $text = '';
        $number = (float)$number;
        if ($number > 0) {
            $number = str_replace('.', '', $number);
            $number = str_replace(',', '', $number);
            $number = (int)$number;
            $text = number_format($number, 0, '', ',');
        }

        return $text;
    }

    public function doiTacDanhSach()
    {
        $select = User::where('ref_code', '<>', NULL)
            ->where('deleted', 0)
            ->where('id', '>', 2)
            ->orderBy('id', 'asc');
        return $select->get();
    }

    //sms
    public function clearSms($phone)
    {
        SmsVerify::where('phone', $phone)
            ->delete();
    }

    public function createSmsCode($phone, $type)
    {
        $viewer = $this->getViewer();
        $apiFE = new FE();

        $values = [
            'phone' => $phone,
            'type' => $type,
            'user_id' => $viewer ? $viewer->id : 0,
            'ip_address' => !$viewer ? $apiFE->getIp() : NULL,
        ];

        do {
            $code = mt_rand(100000, 999999);

            $row = SmsVerify::where('code', $code)
                ->first();
            if (!$row) {
                break;
            }

        } while (1);

        $values['code'] = $code;

        SmsVerify::create($values);

        return $code;
    }

    //report
    public function bcTongDoiTac($month, $year)
    {
        $select = User::selectRaw('COUNT(id) as total')
            ->where('ref_code', '<>', NULL)
            ->where('id', '>', 2)
            //ko tinh super admin
            //van tinh delete user
        ;

        if ($year == 2020 && $month == 11) {
            //tu thang 11 ve truoc
            $select->whereMonth('created_at', '<=', 11)
                ->whereYear('created_at', 2020);
        } else {
            //tinh tung thang
            $select->whereMonth('created_at', (int)$month)
                ->whereYear('created_at', (int)$year);
        }

        $count = $select->get();
        return (int)$count[0]['total'];
    }

    public function bcTongDoiTacNam($year)
    {
        $select = User::selectRaw('COUNT(id) as total')
            ->where('ref_code', '<>', NULL)
            ->where('id', '>', 2)
            //ko tinh super admin
            //van tinh delete user
            ->whereYear('created_at', (int)$year)
        ;

        $count = $select->get();
        return (int)$count[0]['total'];
    }

    public function bcTongDoiTacLuyKe($month, $year)
    {
        $total = 0;

        for ($i = 1; $i <= $month - 1; $i++) {
            $total += $this->bcTongDoiTac($i, $year);
        }

        return $total;
    }

    public function bcTongDoiTacPTKD($month, $year)
    {
        return $this->bcTongDoiTac($month, $year);
    }

    public function bcTongDoiTacPTKDNam($year)
    {
        return $this->bcTongDoiTacNam($year);
    }

    public function bcTongDoiTacPTKDLuyKe($month, $year)
    {
        return $this->bcTongDoiTacLuyKe($month, $year);
    }

    public function bcTongDoiTacHD($month, $year)
    {
        $select = User::query('users')
            ->selectRaw('COUNT(user_carts.refer_id) as total')
            ->distinct()
            ->leftJoin('user_carts', 'users.id', '=', 'user_carts.refer_id')
            ->where('users.ref_code', '<>', NULL)
            ->where('users.id', '>', 2)
            //ko tinh super admin
            //van tinh delete user
        ;

        if ($year == 2020 && $month == 11) {
            //tu thang 11 ve truoc
            $select->whereMonth('user_carts.created_at', '<=', 11)
                ->whereYear('user_carts.created_at', 2020);
        } else {
            //tinh tung thang
            $select->whereMonth('user_carts.created_at', (int)$month)
                ->whereYear('user_carts.created_at', (int)$year);
        }

        $count = $select->get();
        return (int)$count[0]['total'];
    }

    public function bcTongDoiTacHDNam($year)
    {
        $select = User::query('users')
            ->selectRaw('COUNT(user_carts.refer_id) as total')
            ->distinct()
            ->leftJoin('user_carts', 'users.id', '=', 'user_carts.refer_id')
            ->where('users.ref_code', '<>', NULL)
            ->where('users.id', '>', 2)
            //ko tinh super admin
            //van tinh delete user
            ->whereYear('user_carts.created_at', (int)$year)
        ;

        $count = $select->get();
        return (int)$count[0]['total'];
    }

    public function bcTongDoiTacHDLuyKe($month, $year)
    {
        $total = 0;

        for ($i = 1; $i <= $month - 1; $i++) {
            $total += $this->bcTongDoiTacHD($i, $year);
        }

        return $total;
    }

    public function bcTongDoiTacPTKDHD($month, $year)
    {
        $select = User::query('users')
            ->selectRaw('COUNT(user_carts.parent_refer_id) as total')
            ->distinct()
            ->leftJoin('user_carts', 'users.id', '=', 'user_carts.parent_refer_id')
            ->where('users.ref_code', '<>', NULL)
            ->where('users.id', '>', 2)
            //ko tinh super admin
            //van tinh delete user
        ;

        if ($year == 2020 && $month == 11) {
            //tu thang 11 ve truoc
            $select->whereMonth('user_carts.created_at', '<=', 11)
                ->whereYear('user_carts.created_at', 2020);
        } else {
            //tinh tung thang
            $select->whereMonth('user_carts.created_at', (int)$month)
                ->whereYear('user_carts.created_at', (int)$year);
        }

        $count = $select->get();
        return (int)$count[0]['total'];
    }

    public function bcTongDoiTacPTKDHDNam($year)
    {
        $select = User::query('users')
            ->selectRaw('COUNT(user_carts.parent_refer_id) as total')
            ->distinct()
            ->leftJoin('user_carts', 'users.id', '=', 'user_carts.parent_refer_id')
            ->where('users.ref_code', '<>', NULL)
            ->where('users.id', '>', 2)
            //ko tinh super admin
            //van tinh delete user
            ->whereYear('user_carts.created_at', (int)$year)
        ;

        $count = $select->get();
        return (int)$count[0]['total'];
    }

    public function bcTongDoiTacPTKDHDLuyKe($month, $year)
    {
        $total = 0;

        for ($i = $year - 1; $i >= 2020; $i--) {
            $total += $this->bcTongDoiTacPTKDHDNam($i);
        }

        for ($i = 1; $i <= $month - 1; $i++) {
            $total += $this->bcTongDoiTacPTKDHD($i, $year);
        }

        return $total;
    }

    public function bcTongDH($month, $year)
    {
        $select = UserCart::selectRaw('COUNT(id) as total')
            ->whereIn('status', ['da_thanh_toan', 'chua_thanh_toan'])
            //van tinh delete cart
        ;

        if ($year == 2020 && $month == 11) {
            //tu thang 11 ve truoc
            $select->whereMonth('created_at', '<=', 11)
                ->whereYear('created_at', 2020);
        } else {
            //tinh tung thang
            $select->whereMonth('created_at', (int)$month)
                ->whereYear('created_at', (int)$year);
        }

        $count = $select->get();
        return (int)$count[0]['total'];
    }

    public function bcTongDHNam($year)
    {
        $select = UserCart::selectRaw('COUNT(id) as total')
            ->whereIn('status', ['da_thanh_toan', 'chua_thanh_toan'])
            //van tinh delete cart
            ->whereYear('created_at', (int)$year)
        ;

        $count = $select->get();
        return (int)$count[0]['total'];
    }

    public function bcTongDHLuyKe($month, $year)
    {
        $total = 0;

        for ($i = 1; $i <= $month - 1; $i++) {
            $total += $this->bcTongDH($i, $year);
        }

        return $total;
    }

    public function bcTongDHThanhCong($month, $year)
    {
        $select = UserCart::selectRaw('COUNT(id) as total')
            ->where('status', 'da_thanh_toan')
            ->where('deleted', 0)
        ;

        if ($year == 2020 && $month == 11) {
            //tu thang 11 ve truoc
            $select->whereMonth('created_at', '<=', 11)
                ->whereYear('created_at', 2020);
        } else {
            //tinh tung thang
            $select->whereMonth('created_at', (int)$month)
                ->whereYear('created_at', (int)$year);
        }

        $count = $select->get();
        return (int)$count[0]['total'];
    }

    public function bcTongDHThanhCongNam($year)
    {
        $select = UserCart::selectRaw('COUNT(id) as total')
            ->where('status', 'da_thanh_toan')
            ->where('deleted', 0)
            ->whereYear('created_at', (int)$year)
        ;

        $count = $select->get();
        return (int)$count[0]['total'];
    }

    public function bcTongDHThanhCongLuyKe($month, $year)
    {
        $total = 0;

        for ($i = 1; $i <= $month - 1; $i++) {
            $total += $this->bcTongDHThanhCong($i, $year);
        }

        return $total;
    }

    public function bcTongDHHuy($month, $year)
    {
        $select = UserCart::selectRaw('COUNT(id) as total')
            ->whereIn('status', ['da_thanh_toan', 'chua_thanh_toan'])
            ->where('deleted', 1)
        ;

        if ($year == 2020 && $month == 11) {
            //tu thang 11 ve truoc
            $select->whereMonth('created_at', '<=', 11)
                ->whereYear('created_at', 2020);
        } else {
            //tinh tung thang
            $select->whereMonth('created_at', (int)$month)
                ->whereYear('created_at', (int)$year);
        }

        $count = $select->get();
        return (int)$count[0]['total'];
    }

    public function bcTongDHHuyNam($year)
    {
        $select = UserCart::selectRaw('COUNT(id) as total')
            ->whereIn('status', ['da_thanh_toan', 'chua_thanh_toan'])
            ->where('deleted', 1)
            ->whereYear('created_at', (int)$year)
        ;

        $count = $select->get();
        return (int)$count[0]['total'];
    }

    public function bcTongDHHuyLuyKe($month, $year)
    {
        $total = 0;

        for ($i = 1; $i <= $month - 1; $i++) {
            $total += $this->bcTongDHHuy($i, $year);
        }

        return $total;
    }

    public function bcTongDSNET($month, $year)
    {
        $select = UserCart::selectRaw('SUM(total_cart - total_discount) as total')
            ->distinct()
            ->whereIn('status', ['da_thanh_toan', 'chua_thanh_toan'])
            //van tinh delete cart
        ;

        if ($year == 2020 && $month == 11) {
            //tu thang 11 ve truoc
            $select->whereMonth('created_at', '<=', 11)
                ->whereYear('created_at', 2020);
        } else {
            //tinh tung thang
            $select->whereMonth('created_at', (int)$month)
                ->whereYear('created_at', (int)$year);
        }

        $count = $select->get();
        return (int)$count[0]['total'];
    }

    public function bcTongDSNETNam($year)
    {
        $select = UserCart::selectRaw('SUM(total_cart - total_discount) as total')
            ->distinct()
            ->whereIn('status', ['da_thanh_toan', 'chua_thanh_toan'])
            //van tinh delete cart
            ->whereYear('created_at', (int)$year)
        ;

        $count = $select->get();
        return (int)$count[0]['total'];
    }

    public function bcTongDSNETLuyKe($month, $year)
    {
        $total = 0;

        for ($i = 1; $i <= $month - 1; $i++) {
            $total += $this->bcTongDSNET($i, $year);
        }

        return $total;
    }

    public function bcTongDSNETThanhCong($month, $year)
    {
        $select = UserCart::selectRaw('SUM(total_cart - total_discount) as total')
            ->distinct()
            ->where('status', 'da_thanh_toan')
            ->where('deleted', 0)
        ;

        if ($year == 2020 && $month == 11) {
            //tu thang 11 ve truoc
            $select->whereMonth('created_at', '<=', 11)
                ->whereYear('created_at', 2020);
        } else {
            //tinh tung thang
            $select->whereMonth('created_at', (int)$month)
                ->whereYear('created_at', (int)$year);
        }

        $count = $select->get();
        return (int)$count[0]['total'];
    }

    public function bcTongDSNETThanhCongNam($year)
    {
        $select = UserCart::selectRaw('SUM(total_cart - total_discount) as total')
            ->distinct()
            ->where('status', 'da_thanh_toan')
            ->where('deleted', 0)
            ->whereYear('created_at', (int)$year)
        ;

        $count = $select->get();
        return (int)$count[0]['total'];
    }

    public function bcTongDSNETThanhCongLuyKe($month, $year)
    {
        $total = 0;

        for ($i = 1; $i <= $month - 1; $i++) {
            $total += $this->bcTongDSNETThanhCong($i, $year);
        }

        return $total;
    }

    public function bcTongDSNETHuy($month, $year)
    {
        $select = UserCart::selectRaw('SUM(total_cart - total_discount) as total')
            ->distinct()
            ->whereIn('status', ['da_thanh_toan', 'chua_thanh_toan'])
            ->where('deleted', 1)
        ;

        if ($year == 2020 && $month == 11) {
            //tu thang 11 ve truoc
            $select->whereMonth('created_at', '<=', 11)
                ->whereYear('created_at', 2020);
        } else {
            //tinh tung thang
            $select->whereMonth('created_at', (int)$month)
                ->whereYear('created_at', (int)$year);
        }

        $count = $select->get();
        return (int)$count[0]['total'];
    }

    public function bcTongDSNETHuyNam($year)
    {
        $select = UserCart::selectRaw('SUM(total_cart - total_discount) as total')
            ->distinct()
            ->whereIn('status', ['da_thanh_toan', 'chua_thanh_toan'])
            ->where('deleted', 1)
            ->whereYear('created_at', (int)$year)
        ;

        $count = $select->get();
        return (int)$count[0]['total'];
    }

    public function bcTongDSNETHuyLuyKe($month, $year)
    {
        $total = 0;

        for ($i = 1; $i <= $month - 1; $i++) {
            $total += $this->bcTongDSNETHuy($i, $year);
        }

        return $total;
    }

    public function bcTongHHTV($month, $year)
    {
        $select = UserCart::selectRaw('SUM(refer_money) as total')
            ->where('status', 'da_thanh_toan')
            ->where('deleted', 0)
        ;

        if ($year == 2020 && $month == 11) {
            //tu thang 11 ve truoc
            $select->whereMonth('created_at', '<=', 11)
                ->whereYear('created_at', 2020);
        } else {
            //tinh tung thang
            $select->whereMonth('created_at', (int)$month)
                ->whereYear('created_at', (int)$year);
        }

        $count = $select->get();
        return (int)$count[0]['total'];
    }

    public function bcTongHHTVNam($year)
    {
        $select = UserCart::selectRaw('SUM(refer_money) as total')
            ->where('status', 'da_thanh_toan')
            ->where('deleted', 0)
            ->whereYear('created_at', (int)$year)
        ;

        $count = $select->get();
        return (int)$count[0]['total'];
    }

    public function bcTongHHTVLuyKe($month, $year)
    {
        $total = 0;

        for ($i = 1; $i <= $month - 1; $i++) {
            $total += $this->bcTongHHTV($i, $year);
        }

        return $total;
    }

    public function bcTongHHPTHT($month, $year)
    {
        $select = UserCart::selectRaw('SUM(parent_refer_money) as total')
            ->where('status', 'da_thanh_toan')
            ->where('deleted', 0)
        ;

        if ($year == 2020 && $month == 11) {
            //tu thang 11 ve truoc
            $select->whereMonth('created_at', '<=', 11)
                ->whereYear('created_at', 2020);
        } else {
            //tinh tung thang
            $select->whereMonth('created_at', (int)$month)
                ->whereYear('created_at', (int)$year);
        }

        $count = $select->get();
        return (int)$count[0]['total'];
    }

    public function bcTongHHPTHTNam($year)
    {
        $select = UserCart::selectRaw('SUM(parent_refer_money) as total')
            ->where('status', 'da_thanh_toan')
            ->where('deleted', 0)
            ->whereYear('created_at', (int)$year)
        ;

        $count = $select->get();
        return (int)$count[0]['total'];
    }

    public function bcTongHHPTHTLuyKe($month, $year)
    {
        $total = 0;

        for ($i = 1; $i <= $month - 1; $i++) {
            $total += $this->bcTongHHPTHT($i, $year);
        }

        return $total;
    }

    public function bcTongHHTDS($month, $year)
    {
        $commissions = UserCommission::where('month', (int)$month)
            ->where('year', (int)$year)
            ->get();

        if ($year == 2020 && $month <= 11) {
            //du lieu cu ko co
            return 0;
        }

        $total = 0;
        if (count($commissions)) {
             foreach ($commissions as $commission) {
                 $total += $commission->tds_thuc_te;
             }
        }

        return $total;
    }

    public function bcTongHHTDSNam($year)
    {
        $commissions = UserCommission::where('year', (int)$year)
            ->get();

        $total = 0;
        if (count($commissions)) {
            foreach ($commissions as $commission) {
                $total += $commission->tds_thuc_te;
            }
        }

        return $total;
    }

    public function bcTongHHTDSLuyKe($month, $year)
    {
        $total = 0;

        for ($i = 1; $i <= $month - 1; $i++) {
            $total += $this->bcTongHHTDS($i, $year);
        }

        return $total;
    }

    public function bcTongHH($month, $year)
    {
        return $this->bcTongHHTV($month, $year) + $this->bcTongHHPTHT($month, $year) + $this->bcTongHHTDS($month, $year);
    }

    public function bcTongHHNam($year)
    {
        return $this->bcTongHHTVNam($year) + $this->bcTongHHPTHTNam($year) + $this->bcTongHHTDSNam($year);
    }

    public function bcTongHHLuyKe($month, $year)
    {
        $total = 0;

        for ($i = 1; $i <= $month - 1; $i++) {
            $total += $this->bcTongHH($i, $year);
        }

        return $total;
    }

    //
    public function bcTongDHGroup($month, $year, $ids)
    {
        $select = UserCart::selectRaw('COUNT(id) as total')
            ->distinct()
            ->where('status', 'da_thanh_toan')
            ->where('deleted', 0)
            ->where(function ($q) use ($ids) {
                $q->whereIn('user_id', $ids)
                    ->orWhereIn('refer_id', $ids)
                    ->orWhereIn('parent_refer_id', $ids);
            })
            ->whereMonth('created_at', (int)$month)
            ->whereYear('created_at', (int)$year)
        ;

        $count = $select->get();
        return (int)$count[0]['total'];
    }

    public function bcTongDHGroupLuyKe($month, $year, $ids)
    {
//        if ((int)$month == 1) {
//            $date = date(($year - 1) . '-12-t');
//        } else {
//            $date = date($year . '-' . ($month - 1) . '-t');
//        }

        $select = UserCart::selectRaw('COUNT(id) as total')
            ->distinct()
            ->where('status', 'da_thanh_toan')
            ->where('deleted', 0)
            ->where(function ($q) use ($ids) {
                $q->whereIn('user_id', $ids)
                    ->orWhereIn('refer_id', $ids)
                    ->orWhereIn('parent_refer_id', $ids);
            })
//            ->where('created_at', '<=', $date)
            ->whereMonth('created_at', '<', (int)$month)
            ->whereYear('created_at', (int)$year)
        ;

        $count = $select->get();
        return (int)$count[0]['total'];
    }

    public function bcTongDSNETGroup($month, $year, $ids)
    {
        $select = UserCart::selectRaw('SUM(total_cart - total_discount) as total')
            ->distinct()
            ->where('status', 'da_thanh_toan')
            ->where('deleted', 0)
            ->where(function ($q) use ($ids) {
                $q->whereIn('user_id', $ids)
                    ->orWhereIn('refer_id', $ids)
                    ->orWhereIn('parent_refer_id', $ids);
            })
            ->whereMonth('created_at', (int)$month)
            ->whereYear('created_at', (int)$year)
        ;

        $count = $select->get();
        return (int)$count[0]['total'];
    }

    public function bcTongDSNETGroupLuyKe($month, $year, $ids)
    {
//        if ((int)$month == 1) {
//            $date = date(($year - 1) . '-12-t');
//        } else {
//            $date = date($year . '-' . ($month - 1) . '-t');
//        }

        $select = UserCart::selectRaw('SUM(total_cart - total_discount) as total')
            ->distinct()
            ->where('status', 'da_thanh_toan')
            ->where('deleted', 0)
            ->where(function ($q) use ($ids) {
                $q->whereIn('user_id', $ids)
                    ->orWhereIn('refer_id', $ids)
                    ->orWhereIn('parent_refer_id', $ids);
            })
//            ->where('created_at', '<=', $date)
            ->whereMonth('created_at', '<', (int)$month)
            ->whereYear('created_at', (int)$year)
        ;

        $count = $select->get();
        return (int)$count[0]['total'];
    }

    public function bcTDSMuc1($month, $year)
    {
        $muc1 = 0; $muc2 = 0; $muc3 = 0;

        $keyword = 'TDS_' . (int)$month . '_' . (int)$year;
        $setting = Setting::where('title', $keyword)
            ->first();
        if ($setting) {
            $setting = (array)json_decode($setting->value);

            $muc1 = $this->parseToInt($setting['cap_1_doanh_so']);
            $muc2 = $this->parseToInt($setting['cap_2_doanh_so']);
            $muc3 = $this->parseToInt($setting['cap_3_doanh_so']);
        }

        $count = UserCommission::selectRaw('COUNT(id) as total')
            ->where('tds_thuc_te', '>', 0)
            ->where('ds_net_thuc_te', '>=', $muc1)
            ->where('ds_net_thuc_te', '<', $muc2)
            ->where('month', (int)$month)
            ->where('year', (int)$year)
            ->get();

        return (int)$count[0]['total'];
    }

    public function bcTDSMuc1LuyKe($month, $year)
    {
        $muc1 = 0; $muc2 = 0; $muc3 = 0;

        $keyword = 'TDS_' . (int)$month . '_' . (int)$year;
        $setting = Setting::where('title', $keyword)
            ->first();
        if ($setting) {
            $setting = (array)json_decode($setting->value);

            $muc1 = $this->parseToInt($setting['cap_1_doanh_so']);
            $muc2 = $this->parseToInt($setting['cap_2_doanh_so']);
            $muc3 = $this->parseToInt($setting['cap_3_doanh_so']);
        }

        $count = UserCommission::selectRaw('COUNT(id) as total')
            ->where('tds_thuc_te', '>', 0)
            ->where('ds_net_thuc_te', '>=', $muc1)
            ->where('ds_net_thuc_te', '<', $muc2)
            ->where('month', '<', (int)$month)
            ->where('year', (int)$year)
            ->get();

        return (int)$count[0]['total'];
    }

    public function bcTDSMuc1Nam($year)
    {
        $total = 0;

        for ($i = 1; $i <= 12; $i++) {
            if ($year == 2020 && $i < 10) {
                continue;
            }

            $muc1 = 0; $muc2 = 0; $muc3 = 0;

            $keyword = 'TDS_' . (int)$i . '_' . (int)$year;
            $setting = Setting::where('title', $keyword)
                ->first();
            if ($setting) {
                $setting = (array)json_decode($setting->value);

                $muc1 = $this->parseToInt($setting['cap_1_doanh_so']);
                $muc2 = $this->parseToInt($setting['cap_2_doanh_so']);
                $muc3 = $this->parseToInt($setting['cap_3_doanh_so']);
            }

            $rows = UserCommission::where('tds_thuc_te', '>', 0)
                ->where('ds_net_thuc_te', '>=', $muc1)
                ->where('ds_net_thuc_te', '<', $muc2)
                ->where('month', (int)$i)
                ->where('year', (int)$year)
                ->get();

            $arr = [];
            if (count($rows)) {
                foreach ($rows as $row) {
                    if (!in_array($row->user_id, $arr)) {
                        $total++;

                        $arr[] = $row->user_id;
                    }
                }
            }
        }

        return $total;
    }

    public function bcTDSMuc2($month, $year)
    {
        $muc1 = 0; $muc2 = 0; $muc3 = 0;

        $keyword = 'TDS_' . (int)$month . '_' . (int)$year;
        $setting = Setting::where('title', $keyword)
            ->first();
        if ($setting) {
            $setting = (array)json_decode($setting->value);

            $muc1 = $this->parseToInt($setting['cap_1_doanh_so']);
            $muc2 = $this->parseToInt($setting['cap_2_doanh_so']);
            $muc3 = $this->parseToInt($setting['cap_3_doanh_so']);
        }

        $count = UserCommission::selectRaw('COUNT(id) as total')
            ->where('tds_thuc_te', '>', 0)
            ->where('ds_net_thuc_te', '>=', $muc2)
            ->where('ds_net_thuc_te', '<', $muc3)
            ->where('month', (int)$month)
            ->where('year', (int)$year)
            ->get();

        return (int)$count[0]['total'];
    }

    public function bcTDSMuc2LuyKe($month, $year)
    {
        $muc1 = 0; $muc2 = 0; $muc3 = 0;

        $keyword = 'TDS_' . (int)$month . '_' . (int)$year;
        $setting = Setting::where('title', $keyword)
            ->first();
        if ($setting) {
            $setting = (array)json_decode($setting->value);

            $muc1 = $this->parseToInt($setting['cap_1_doanh_so']);
            $muc2 = $this->parseToInt($setting['cap_2_doanh_so']);
            $muc3 = $this->parseToInt($setting['cap_3_doanh_so']);
        }

        $count = UserCommission::selectRaw('COUNT(id) as total')
            ->where('tds_thuc_te', '>', 0)
            ->where('ds_net_thuc_te', '>=', $muc2)
            ->where('ds_net_thuc_te', '<', $muc3)
            ->where('month', '<', (int)$month)
            ->where('year', (int)$year)
            ->get();

        return (int)$count[0]['total'];
    }

    public function bcTDSMuc2Nam($year)
    {
        $total = 0;

        for ($i = 1; $i <= 12; $i++) {
            if ($year == 2020 && $i < 10) {
                continue;
            }

            $muc1 = 0; $muc2 = 0; $muc3 = 0;

            $keyword = 'TDS_' . (int)$i . '_' . (int)$year;
            $setting = Setting::where('title', $keyword)
                ->first();
            if ($setting) {
                $setting = (array)json_decode($setting->value);

                $muc1 = $this->parseToInt($setting['cap_1_doanh_so']);
                $muc2 = $this->parseToInt($setting['cap_2_doanh_so']);
                $muc3 = $this->parseToInt($setting['cap_3_doanh_so']);
            }

            $rows = UserCommission::where('tds_thuc_te', '>', 0)
                ->where('ds_net_thuc_te', '>=', $muc2)
                ->where('ds_net_thuc_te', '<', $muc3)
                ->where('month', (int)$i)
                ->where('year', (int)$year)
                ->get();

            $arr = [];
            if (count($rows)) {
                foreach ($rows as $row) {
                    if (!in_array($row->user_id, $arr)) {
                        $total++;

                        $arr[] = $row->user_id;
                    }
                }
            }
        }

        return $total;
    }

    public function bcTDSMuc3($month, $year)
    {
        $muc1 = 0; $muc2 = 0; $muc3 = 0;

        $keyword = 'TDS_' . (int)$month . '_' . (int)$year;
        $setting = Setting::where('title', $keyword)
            ->first();
        if ($setting) {
            $setting = (array)json_decode($setting->value);

            $muc1 = $this->parseToInt($setting['cap_1_doanh_so']);
            $muc2 = $this->parseToInt($setting['cap_2_doanh_so']);
            $muc3 = $this->parseToInt($setting['cap_3_doanh_so']);
        }

        $count = UserCommission::selectRaw('COUNT(id) as total')
            ->where('tds_thuc_te', '>', 0)
            ->where('ds_net_thuc_te', '>=', $muc3)
            ->where('month', (int)$month)
            ->where('year', (int)$year)
            ->get();

        return (int)$count[0]['total'];
    }

    public function bcTDSMuc3LuyKe($month, $year)
    {
        $muc1 = 0; $muc2 = 0; $muc3 = 0;

        $keyword = 'TDS_' . (int)$month . '_' . (int)$year;
        $setting = Setting::where('title', $keyword)
            ->first();
        if ($setting) {
            $setting = (array)json_decode($setting->value);

            $muc1 = $this->parseToInt($setting['cap_1_doanh_so']);
            $muc2 = $this->parseToInt($setting['cap_2_doanh_so']);
            $muc3 = $this->parseToInt($setting['cap_3_doanh_so']);
        }

        $count = UserCommission::selectRaw('COUNT(id) as total')
            ->where('tds_thuc_te', '>', 0)
            ->where('ds_net_thuc_te', '>=', $muc3)
            ->where('month', '<', (int)$month)
            ->where('year', (int)$year)
            ->get();

        return (int)$count[0]['total'];
    }

    public function bcTDSMuc3Nam($year)
    {
        $total = 0;

        for ($i = 1; $i <= 12; $i++) {
            if ($year == 2020 && $i < 10) {
                continue;
            }

            $muc1 = 0; $muc2 = 0; $muc3 = 0;

            $keyword = 'TDS_' . (int)$i . '_' . (int)$year;
            $setting = Setting::where('title', $keyword)
                ->first();
            if ($setting) {
                $setting = (array)json_decode($setting->value);

                $muc1 = $this->parseToInt($setting['cap_1_doanh_so']);
                $muc2 = $this->parseToInt($setting['cap_2_doanh_so']);
                $muc3 = $this->parseToInt($setting['cap_3_doanh_so']);
            }

            $rows = UserCommission::where('tds_thuc_te', '>', 0)
                ->where('ds_net_thuc_te', '>=', $muc3)
                ->where('month', (int)$i)
                ->where('year', (int)$year)
                ->get();

            $arr = [];
            if (count($rows)) {
                foreach ($rows as $row) {
                    if (!in_array($row->user_id, $arr)) {
                        $total++;

                        $arr[] = $row->user_id;
                    }
                }
            }
        }

        return $total;
    }
}
