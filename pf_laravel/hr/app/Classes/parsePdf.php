<?php
namespace App\Classes;

use App\Models\Language;
use App\Models\LanguageLevel;
use App\Models\Skill;
use App\Models\Specialization;
use \Gufy\PdfToHtml\Base;
use \phpQuery;
use PHPUnit\Exception;


class parsePdf {

    protected $month = ['январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь'];

    protected $pmonth = ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];

    protected $page = '';

    protected $pq = false;

    protected $currentElement = false;

    public $worker;

    public function parse($pdf) {
        // change pdftohtml bin location
        try {

            $pdfToHtml = new Base($pdf->getRealPath());
            $pdfToHtml->setOutputDirectory(storage_path('html'));
            $pdfToHtml->generate();

            $html = file_get_contents(storage_path('html') . '/' . str_replace('.pdf', '.html', $pdf->getFileName()));
            $html = iconv(mb_detect_encoding($html), "UTF-8", $html);

        } catch (Exception $e) {
            dd($e->getMessage());
        }

        $pDoc = phpQuery::newDocument($html);

        $this->page = '';
        $this->page .= $pDoc->find('div')->html();
        unset($pDoc);

        if (!empty($this->page)) {
            $this->pq = phpQuery::newDocumentHTML($this->page);

            $this->worker = new \stdClass();
            $this->getName();
            $this->currentElement = $this->currentElement->next();
            $arrInfo = explode(',', $this->currentElement->text());
            $this->worker->sex = (isset($arrInfo[0]) && $arrInfo[0]) ? trim($arrInfo[0]) : null;
            $this->worker->age = (isset($arrInfo[1]) && $arrInfo[1]) ? intval($arrInfo[1]) : null;
            $bdate = trim(str_replace('родился', '', $arrInfo[2]));
            $this->worker->birthday = (isset($arrInfo[2]) && $arrInfo[2] && $bdate) ? $this->getFormattedBirthDate($bdate) : null;
            $this->currentElement = $this->currentElement->next();
            $arrInfo = explode('<br>', $this->currentElement->html());
            $this->worker->phone = (isset($arrInfo[0]) && $arrInfo[0]) ? $arrInfo[0] : null;
            $this->worker->email = (isset($arrInfo[1]) && $arrInfo[1]) ? $arrInfo[1] : null;
            $this->getAddress();
            $this->getSpec();
            $this->getJobs();
            $this->getWorks();
            $this->getEducation();
            $this->getLanguages();
            $this->getSkills();
            $this->getAddition();
            $this->getImage();

            return $this->worker;
        }

        return false;
    }

    protected function mb_str_replace($search, $replace, $string)
    {
        $charset = mb_detect_encoding($string);

        $unicodeString = iconv($charset, "UTF-8", $string);

        return str_replace($search, $replace, $unicodeString);
    }

    protected function getFormattedDate($date) {
        $date = trim(rawurlencode($date));
        $date = $this->mb_str_replace("%C2", '', $date);
        $date = $this->mb_str_replace("%A0", '|', $date);
        $date = urldecode($date);
        $arr = explode("|", $date);
        $arr = array_values(array_diff($arr, array('')));
        $item = trim(mb_strtolower($arr[0]));
        if (in_array($item, $this->month)) {
            $mm = array_search ($item, $this->month);
            return date('Y-m-d', strtotime('01.' . $mm . '.' . trim($arr[1])));
        }
        return null;
    }

    protected function getFormattedBirthDate($date) {
        $date = trim(rawurlencode($date));
        $date = $this->mb_str_replace("%C2", '', $date);
        $date = $this->mb_str_replace("%A0", '|', $date);
        $date = urldecode($date);
        $arr = explode("|", $date);
        $arr = array_values(array_diff($arr, array('')));
        $item = trim(mb_strtolower($arr[1]));
        if (in_array($item, $this->pmonth)) {
            $mm = array_search ($item, $this->pmonth) + 1;
            return date('Y-m-d', strtotime(trim($arr[0]) . '.' . $mm . '.' . trim($arr[2])));
        }
        return null;
    }

    protected function getName() {
        if ((strpos($this->pq->find('p')->eq(0)->text(), "Отклик") !== false)
            || (strpos($this->pq->find('p')->eq(0)->text(), "Приглашен") !== false)) {
            $this->currentElement = $this->pq->find('p')->eq(1);
        } else {
            $this->currentElement = $this->pq->find('p')->eq(0);
        }
        $this->worker->name = $this->currentElement->text();
        if (strpos($this->currentElement->next()->text(), ',') === false) {
            $this->worker->name .= ' ' . $this->currentElement->next()->text();
            $this->currentElement = $this->currentElement->next();
        }

        $this->worker->last_name = $this->worker->name;
        $this->worker->first_name= $this->worker->middle_name= "";
        $arrInfo= explode(" ", preg_replace('~\xc2\xa0~', ' ', $this->worker->name));

        if (count($arrInfo)>1) {
            $this->worker->last_name = $arrInfo[0];
            $this->worker->first_name= $arrInfo[1];
            $this->worker->middle_name= (count($arrInfo)>2) ? $arrInfo[2] : "";
        }
    }

    protected function findEmail($str) {
        $pattern = "/[-a-z0-9!#$%&'*_`{|}~]+[-a-z0-9!#$%&'*_`{|}~\.=?]*@[a-zA-Z0-9_-]+[a-zA-Z0-9\._-]+/i";
        preg_match_all($pattern, $str, $result);
        $r = array_unique(array_map(function ($i) { return $i[0]; }, $result));
        return count($r) ? $r[0] : '';
    }

    protected function findSkype($str) {
        $re = '/Skype:\s(.+)/m';
        preg_match_all($re, $str, $result, PREG_SET_ORDER, 0);
        $r = array_unique(array_map(function ($i) { return $i[0]; }, $result));
        return count($r) ? $r[0] : '';
    }

    protected function getAddress() {
        $this->worker->email = $this->findEmail($this->currentElement->html());
        $this->worker->skype = trim(str_replace('Skype:', '', $this->findSkype($this->currentElement->html())));
        $this->currentElement = $this->currentElement->next();
        $arrInfo = explode('<br>', $this->currentElement->html());
        $this->worker->live = (isset($arrInfo[0]) && $arrInfo[0]) ? trim(str_replace('Проживает:', '', $arrInfo[0])) : null;
        $this->worker->citizen = (isset($arrInfo[1]) && $arrInfo[1]) ? trim(str_replace('Гражданство:', '', $arrInfo[1])) : null;
        $this->worker->move = (isset($arrInfo[2]) && $arrInfo[2]) ? trim($arrInfo[2]) : null;
    }

    private function nbSpaceReplace($str) {
        return preg_replace('~\xc2\xa0~', ' ', $str);
    }

    protected function getSpec() {
        $pUser = $this->pq->find('p:contains("Желаемая")');
        if (trim($pUser->text())) {
            $this->worker->worker = $pUser->next()->text();
            $spec = trim(str_replace('Специализации:', '', $pUser->next()->text()));
            $arrSpec = ($spec) ? preg_split("/(,|—)/", $spec) : [];
            foreach( $arrSpec as $spec_) {
                $spec = ucwords(trim($this::nbSpaceReplace($spec_)));
                $rec = Specialization::where('name',$spec)->first();
                if ($rec) {
                    $this->worker->specialization_id = $rec->id;
                    return;
                }
            }
            $this->worker->spec = (empty($arrSpec)) ? null : implode(', ', $arrSpec);
        }
    }

    protected function getArrLast($array) {
        $arr = explode(':', $array);
        return (isset($array) && $array) ? trim(end($arr)) : null;
    }

    protected function getJobs() {
        $pUser = $this->pq->find('p:contains("Занятость:")');
        if (trim($pUser->text())) {
            $arrInfo = explode('<br>', $pUser->html());

            $this->worker->job = $this->getArrLast($arrInfo[0]);
            $this->worker->schedule = $this->getArrLast($arrInfo[1]);
            $this->worker->travel_time = $this->getArrLast($arrInfo[2]);
        }
    }

    private function getWorkDate($str) {
        $str = mb_strtolower(trim($this::nbSpaceReplace($str)));
        $arr = explode(' ', $str);
        if (in_array($arr[0], $this->month)) {
            $mm = array_search ($arr[0], $this->month) + 1;
            return sprintf("%s-%02s-01", trim($arr[1]), strval($mm));
        }
        return '';
    }

    private function goNext($search, $limit=10) {
        $i = 0;
        $find = false;

        do {
            $this->currentElement = $this->currentElement->next();
            $find = mb_strpos($this->currentElement->text(), $search);
            $i++;
        } while (($find === false) && ($i < $limit));

        return ($find === false) ? false : true;
    }

    protected function getWorks() {
        //$pWork = $this->pq->find('p:contains("Опыт")');
        if ($this->goNext("Опыт")) {
            $arrInfo = explode('<br>', $this->currentElement->html());
            $this->worker->experience = (isset($arrInfo[0]) && $arrInfo[0] && (strpos($arrInfo[0], '—')!==false)) ? trim(explode('—', $arrInfo[0])[1]) : null;

            // места работы - до "Резюме&#160;обновлено"
            $first = true;
            $stop = false;
            $data = array();
            $duties = '';
            $next_company= false;
            $company = "";
            do {
                if ($first) {
                    $dt1 = $this->getWorkDate($arrInfo[1]);
                    $dt2 = $this->getWorkDate($arrInfo[2]);
                    $current = (mb_strpos($arrInfo[2],'настоящее') !== false) ? 1 : 0;
                    $find_date = false;
                    $text = '';
                    $next_company= true;
                } else {
                    $this->currentElement = $this->currentElement->next();
                    $text = $this->currentElement->text();
                    $find_date = (mb_strpos($text, '—') !== false) ? true : false;
                    $stop = ((mb_strpos($text, 'Резюме') !== false) && (mb_strpos($text, 'обновлено') !== false)) ? true : false;
                    if (!$stop && $next_company) {
                        $next_company = false;
                        $arrInfo = explode('<br>', $this->currentElement->html());
                        $company = strip_tags( str_replace('<b>', '',$this::nbSpaceReplace($arrInfo[0]) ));
                    }
                }
                if ($find_date) {
                    // added new data
                    $data[] = [
                        'company' => $company,
                        'date_start' => $dt1,
                        'date_end' => $dt2,
                        'current' => $current,
                        'duties' => $duties
                    ];
                    $duties= '';
                    $arrInfo = explode('<br>', $this->currentElement->html());
                    $dt1 = $this->getWorkDate($arrInfo[0]);
                    $dt2 = $this->getWorkDate($arrInfo[1]);
                    $current = (mb_strpos($arrInfo[1],'настоящее') !== false) ? 1 : 0;
                    $next_company= true;
                } else {
                    if (!$stop && !$first) {
                        $duties .= strip_tags(
                            str_replace(['<b>','</b>'], '',
                            str_replace('<br>', ' ', $this::nbSpaceReplace($this->currentElement->html()))
                            )).' ';
                    }
                }
                $first = false;
            } while (!$stop);
            // added new data
            $data[] = [
                'company' => $company,
                'date_start' => $dt1,
                'date_end' => $dt2,
                'current' => $current,
                'duties' => $duties
            ];

            $this->worker->placeWork = $data;
            /*foreach ($data as $item) {
                echo "Element <br>";
                foreach ($item as $key => $el) {
                    echo $key.": ".$el."<br>";
                }
                echo "<br>";
            }
            throw new \Exception("ssgasd");*/
        }
    }

    protected function getEducation() {
        $pUser = $this->pq->find('p:contains("Образование")')->eq(0);
        if (trim($pUser->text()))
            $this->worker->education = trim(str_replace('Образование', '', $pUser->text()));
    }

    protected function setNext($el) {
        if (mb_stripos($el->text(), 'обновлено') !== false) {
            $el = $el->next();
        }
        return $el;
    }

    protected function getLanguages() {
        $pUser = $this->pq->find('p:contains("языков")');
        if (trim($pUser->text())) {
            $el = $pUser->next();
            $arrInfo = explode('<br>', $el->html());
            $arrName = [];
            $arrLevel= [];
            foreach ($arrInfo as $lang) {
                if (($pos = mb_strpos($lang,'—')) !== false) {
                    $nameLang = mb_substr($lang, 0, $pos);
                    $levelLang = mb_substr($lang, $pos + 1);
                    $nameLang = ucwords(trim($this::nbSpaceReplace($nameLang)));
                    $rec = Language::where('name', $nameLang)->first();
                    if ($rec) {
                        $arrName[] = $rec->id;

                        $levelLang = strtolower(str_replace(' —', '', $levelLang));
                        $levelLang = ucwords(trim($this::nbSpaceReplace($levelLang)));
                        $rec2 = LanguageLevel::where('name', $levelLang)->first();
                        if ($rec2)
                            $arrLevel[] = $rec2->id;
                    }
                }
            }
            $this->worker->languages = (count($arrName)==0) ? null : implode(',', $arrName);
            $this->worker->language_levels = (count($arrLevel)==0) ? null : implode(',', $arrLevel);
        }
    }

    protected function getSkills() {
        $pUser = $this->pq->find('p:contains("Навыки")')->eq(1);
        if (trim($pUser->text())) {
            $el = $pUser->next();
            $this->worker->skills = '';
            $name = str_replace('<br>', ' ', $el->html());
            $name = $this::nbSpaceReplace($name);
            $skills = explode('   ', $name);
            $arrSkills= [];
            foreach($skills as $skill) {
                if (!empty($skill)) {
                    $rec = Skill::where('name', trim($skill))->first();
                    if ($rec)
                        $arrSkills[] = $rec->id;
                    else
                        $arrSkills[] = $skill;
                }
            }
            $this->worker->skills = $arrSkills;
        }
    }

    protected function getAddition() {
        $pUser = $this->pq->find('p:contains("Дополнительная")');
        if (trim($pUser->text())) {
            $el = $pUser->next()->next();
            $name = strip_tags( str_replace('<br>', ' ', $el->html()) );
            $this->worker->comment = trim($name);
        }
    }

    protected function getImage() {
        $img = $this->pq->find('img')->eq(1)->attr('src');

        $img2= substr_replace($img,"-1_".substr($img,-5,1), -7, 3);
        if ($img2 && file_exists(storage_path('html') . '/' .$img2))
            $img= $img2;

        if ($img && file_exists(storage_path('html') . '/' .$img)) {
            $this->worker->img = $img;
            //$path = public_path() . '/img/' . $img;
            $path = storage_path() . '/app/public/images/' . $img;
            file_put_contents($path, file_get_contents(storage_path('html') . '/' .$img));
            $this->imgCrop($path);
            $this->worker->photo = 'images/' . $img;
        }
    }

    private function imgCrop($filename) {
        $im = imagecreatefrompng($filename);
        $size = min(imagesx($im), imagesy($im));
        $im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $size, 'height' => $size]);
        if ($im2 !== FALSE) {
            imagepng($im2, $filename);
            imagedestroy($im2);
        }
        imagedestroy($im);
    }
}
