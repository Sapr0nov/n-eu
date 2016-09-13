<?php
namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class Status extends Model
{
    public $permissions;
	public $uploadFile;
	public $xml;
	
    public function rules()
    {
        return [
            [['uploadFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'xml'],
        ];
    }	

    public function getPermissions() {
      return array ('articulus','article');
    }

    public function getPermissionsLabel($permissions) {
        return $permissions;
    }

	public function upload()
    {	
		$file_info = UploadedFile::getInstance($this, 'uploadFile');
		if ($file_info && $this->validate()) {                
            $result = $file_info->saveAs('uploads/' . date('U') . '.' . $file_info->extension);
			$this->xml = date('U') . '.' . $file_info->extension;
				return $result;
		} else {
				return false;
		}
	}

	public function delete_upload()
    {	
		array_map("unlink", glob("uploads/*.xml"));
	}
	
	public function xml2db($xmlURL)
    {	
		$mysql = mysql_connect("localhost","zaotoni","HYTSSbY5");
		mysql_query("SET NAMES 'utf8'");
		mysql_select_db("zaotoni_vestnik",$mysql);

		$sxml = simplexml_load_file($xmlURL);
		$result = $sxml;
		$type_xml = 0;
		if ($sxml->operCard->operator <> '') { $type_xml = 1; echo "Новый тип файла xml. Работаем.";}
		if ($sxml->opercard->operator <> '') { $type_xml = 2; echo "Устаревший тип файла xml. (2012 года). Пробуем обработать.";}

// добавить Проверки наличия переменных

		/*		таблица issue
			xml				db
		issue->nubmer	(num)
		issue->dataUni 	(year)
		issue->pages 	?
		?				(content_file)
*/
		$id_issue = NULL; 
		$number = mysql_real_escape_string(stripslashes($sxml->issue->number));
		$dataUni = mysql_real_escape_string(stripslashes($sxml->issue->dateUni));
				 // Проверка на наличие article в БД  (проверка через article_description)
			$result = mysql_query("SELECT `id` FROM `zaotoni_vestnik`.`issue` WHERE `year` = '".$dataUni."' && `num` = '".$number."'");
				while ($row = mysql_fetch_array($result)) {
					if ($row['id']<>'') {
						$id_issue = $row['id'];
					}
				}
	if (is_null($id_issue)) {
		$result = mysql_query("INSERT INTO `zaotoni_vestnik`.`issue` (`id`, `year`, `num`, `content_file`, `use_content_file`,  `issue_file`, `use_issue_file`, `touch_stat`, `pub_data`, `last_edit_data`,`published`) VALUES ('".NULL."', '".$dataUni."', '".$number."','".NULL."','".NULL."','".NULL."','".NULL."','".NULL."','".date("Y-m-d H:i:s")."','".NULL."','".NULL."');");
		$id_issue = mysql_insert_id();
	}
/*		CATEGORY
		id 
		article_category_id	 // 0 - русский 
		$title	
		language
*/
		$articles = $sxml->issue->articles[0];
		$article_category_id = NULL;
		foreach($articles as $key => $article ) {
			$issue_sort = 0;
			if ($key =='section') {									// первая секция задает категорию для статей ниже.
				$title = mysql_real_escape_string(stripslashes($article->secTitle[0])); 
				$title_en = mysql_real_escape_string(stripslashes($article->secTitle[1])); 
				$lang = mysql_real_escape_string(stripslashes($article->secTitle[0]['lang'])); 
				$lang_en = mysql_real_escape_string(stripslashes($article->secTitle[1]['lang'])); 

				$article_category_id = NULL; // Проверка на наличие category в БД  (Проверяется только русская версия категории)
				$result = mysql_query("SELECT `id` FROM `zaotoni_vestnik`.`category` WHERE `title` = '".$title."'");
					while ($row = mysql_fetch_array($result)) {
						if ($row['id']<>'') {
							$article_category_id = $row['id'];
						}
					}
					if (is_null($article_category_id)) {  
						$result = mysql_query("INSERT INTO `zaotoni_vestnik`.`category` (`id`, `article_category_id`, `title`, `language`) VALUES (NULL,'0', '".$title."', '".$lang."');");
						$article_category_id = mysql_insert_id();
						$result = mysql_query("INSERT INTO `zaotoni_vestnik`.`category` (`id`, `article_category_id`, `title`, `language`) VALUES (NULL,'".$article_category_id."', '".$title_en."', '".$lang_en."');");
					}else{
						$message = "Категория - ".$title."  уже есть в базе <br/>";
						echo $message;
					}
					
			} else // article
			{	
				$pages = mysql_real_escape_string(stripslashes($article->pages[0]));
				$artType = mysql_real_escape_string(stripslashes($article->artType[0])); // ? нет в БД
				$artTitle = mysql_real_escape_string(stripslashes($article->artTitles->artTitle[0]));
				$artTitle_en = mysql_real_escape_string(stripslashes($article->artTitles->artTitle[1]));
				$abstract = mysql_real_escape_string(stripslashes($article->abstracts->abstract[0]));
				$abstract_en = mysql_real_escape_string(stripslashes($article->abstracts->abstract[1]));
//				if (isset($article->abstracts->abstract[1]['lang'])) $lang = $article->abstracts->abstract[1]['lang']; //Если делать проверку на соответстие атрибута Eng/Rus языку
				$file = mysql_real_escape_string(stripslashes($article->files->file));
				$text = mysql_real_escape_string(stripslashes($article->text[0]['lang']));
				$doi = mysql_real_escape_string(stripslashes($article->doi[0]));	
/*		
				ARTICLE
				id
				journal_issue_id = $id_issue
				article_category_id = 0   $article_category_id
				pages 				$pages
				article_file      	$file
				pub_date 			date("Y-m-d H:i:s")
				issue_sort 			$issue_sort
				doi //на будущее	$doi
				
				article_description
				id
				title			$artTitle  rus/eng
				abstract		$abstract lang rus/eng
				article_id		=0
				language		text lang
				
				article_description_en
				id
				title			$artTitle_en  rus/eng
				abstract		$abstract_en lang rus/eng
				article_id		=0
*/
				$article_id = NULL; // Проверка на наличие article в БД  (проверка через article_description)
				$result = mysql_query("SELECT `id` FROM `zaotoni_vestnik`.`article_description` WHERE `title` = '".$artTitle."' && `abstract` = '".$abstract."'");
				while ($row = mysql_fetch_array($result)) {
					if ($row['id']<>'') {
						$article_id = $row['id'];
					}
				}
				if (is_null($article_id)) {
					$issue_sort++;
					$result = mysql_query("INSERT INTO `zaotoni_vestnik`.`article` (`id`, `journal_issue_id`, `article_category_id`, `pages`, `article_file`, `touch_stat`, `pub_date`, `last_edit_date`, `published`, `issue_sort`, `doi`) VALUES (NULL,'".$id_issue."', '".$article_category_id."', '".$pages."', '".$file."', '0', '".date("Y-m-d H:i:s")."', '', '1', '".$issue_sort."', '".$doi."');");
					$article_id = mysql_insert_id();
					$result = mysql_query("INSERT INTO `zaotoni_vestnik`.`article_description` (`id`, `title`, `abstract`, `article_id`, `language`) VALUES (NULL,'".$artTitle."', '".$abstract."', '".$article_id."', '".$text."');");
					$result = mysql_query("INSERT INTO `zaotoni_vestnik`.`article_description_en` (`id`, `title`, `abstract`, `article_id`) VALUES (NULL,'".$artTitle_en."', '".$abstract_en."', '".$article_id."');");
				}else{
					$message = "Статья - ".$artTitle."  уже есть в базе <br/>";
					echo $message;
				}
				$author_sort=NULL;
				foreach ($article->authors->author as $authors => $author) {  //авторы  
					$author_sort++;
					$email = mysql_real_escape_string(stripslashes($author->individInfo[0]->email));
					$surname = mysql_real_escape_string(stripslashes($author->individInfo[0]->surname));
					$initials = mysql_real_escape_string(stripslashes($author->individInfo[0]->initials));
					$lang = mysql_real_escape_string(stripslashes($this->xml_attribute($author->individInfo[0],'lang')));
					$email_en = mysql_real_escape_string(stripslashes($author->individInfo[1]->email));
					$surname_en = mysql_real_escape_string(stripslashes($author->individInfo[1]->surname));
					$initials_en = mysql_real_escape_string(stripslashes($author->individInfo[1]->initials));
					$lang_en = mysql_real_escape_string(stripslashes($this->xml_attribute($author->individInfo[1],'lang')));
				
				
/*					id	+
					affiliation ? //другая версия файла? ид вуза? а где сам вуз?
					other + individInfo otherInfo
					article_author_id $author_id
					langauge individInfo 
*/				
				$author_id = NULL; // Проверка на наличие автора в БД 
					$result = mysql_query("SELECT `id` FROM `zaotoni_vestnik`.`author` WHERE `email` = '".$email."' && `surname`='".$surname."'");
					while ($row = mysql_fetch_array($result)) {
						if ($row['id']<>'') {
							$author_id = $row['id'];
						}
					}
					if (is_null($author_id)) {
						$result = mysql_query("INSERT INTO `zaotoni_vestnik`.`author` (`id`, `email`, `surname`, `lastname`, `language`) VALUES (NULL,'".$email."', '".$surname."', '".$initials."', '".$lang."');");
						$author_id = mysql_insert_id();
						$result = mysql_query("INSERT INTO `zaotoni_vestnik`.`author` (`id`, `email`, `surname`, `lastname`, `language`) VALUES (NULL,'".$email_en."', '".$surname_en."', '".$initials_en."', '".$lang_en."');");
						$result = mysql_query("INSERT INTO `zaotoni_vestnik`.`article_author` (`id`, `article_id`, `author_id`, `author_sort`) VALUES (NULL,'".$article_id."', '".$author_id."', '".$author_sort."');");
					}else{
						$message = "Автор - ".$surname." / ".$surname_en." уже есть в базе <br/>";
						echo $message;
					}	
				}
				$i = 0;
				if (isset($article->keywords->kwdGroup->keyword)) {
					foreach ($article->keywords->kwdGroup->keyword as $keywords => $keyword) {  //keywords
						$keyword = mysql_real_escape_string(stripslashes($keyword));
						$keyword_id = NULL; // Проверка на наличие keyword в БД 
						$keyword_article_id = NULL; 
							$result = mysql_query("SELECT `id` FROM `zaotoni_vestnik`.`keyword` WHERE `keyword` = '".$keyword."'");
							while ($row = mysql_fetch_array($result)) {
								if ($row['id']<>'') { $keyword_id = $row['id'];}
							}
							if (is_null($keyword_id)) {
								$result = mysql_query("INSERT INTO `zaotoni_vestnik`.`keyword` (`id`, `keyword`) VALUES (NULL,'".$keyword."');");
								$keyword_id = mysql_insert_id();
							}else{
								$message = "Ключевик - ".$keyword."  уже есть в базе <br/>";
								echo $message;
							}
							$result = mysql_query("SELECT `id` FROM `zaotoni_vestnik`.`article_keyword` WHERE `article_id`='".$article_id."' && `keyword_id`='".$keyword_id."';");
							while ($row = mysql_fetch_array($result)) {
								if ($row['id']<>'') { $keyword_article_id = $row['id'];}
							}
							if (is_null($keyword_article_id)) {
								$i++;
								$result = mysql_query("INSERT INTO `zaotoni_vestnik`.`article_keyword` (`id`,`article_id`,`keyword_id`, `position`) VALUES (NULL,'".$article_id."','".$keyword_id."','".$i."');");	
							}else{
								$message = "Связь статья (".$article_id.") - ключевик (".$keyword_id.") уже есть в базе <br/>";
								echo $message;
							}
					}
				}
				// reference
				$i = 0;
				$langleng = 0;
				$langDolya = 0;
				$reference_lang = 'ANY'; // по умолчанию язык английский
				if (isset($article->references->reference)) {
					foreach ($article->references->reference as $references => $reference) {  //reference
						$reference = mysql_real_escape_string(stripslashes($reference));
						$reference_id = NULL; // Проверка на наличие reference в БД 
							$result = mysql_query("SELECT `id` FROM `zaotoni_vestnik`.`article_reference` WHERE `article_id`='".$article_id."' && `reference` = '".$reference."'");
							while ($row = mysql_fetch_array($result)) {
								if ($row['id']<>'') {	$reference_id = $row['id'];}
							}
							if (is_null($reference_id)) {
								$i++; 
								$langDolya = mb_strlen($reference)/mb_strlen($reference, 'UTF-8');
								if ($langDolya > 1.4) {$reference_lang = 'RUS'; } // если русских букв больше язык - русский 1.15 - Внимание
								if ($langDolya < 1.15) {$reference_lang = 'ENG'; } 
								if (($langDolya < 1.4) AND ($langDolya > 1.15)) {
									$reference_lang = 'ANY';
									$message = "<b>Не удалось определить язык - ".$reference."</b><br/>";
									echo $message;
								}
								if (($langDolya < 1.15) AND ($langDolya > 1.05)) {
									$message = "<b>Слишком много русских букв для простой опечатки - ".$reference."</b><br/>";
									echo $message;
								}
								$result = mysql_query("INSERT INTO `zaotoni_vestnik`.`article_reference` (`id`,`article_id`,`reference`, `position`, `language`) VALUES (NULL,'".$article_id."','".$reference."','".$i."','".$reference_lang."');");	
							}else{
								$message = "Reference - ".$reference."  уже есть в базе <br/>";
								echo $message;
							}
					}
				}
			}		
		}


		
		// Проверка шаблона вынести в начало до загрузки данных :)
		libxml_use_internal_errors(true);
		 
		$xml = new \DOMDocument();
		$xml->load($xmlURL);
		 
		if ($type_xml==1) { $xsdPath = 'uploads/template1.xsd'; }else {$xsdPath = 'uploads/template2.xsd'; }
		
		
		 
		if (!$xml->schemaValidate($xsdPath)) {
			print '<b>При проверке обнаружены ошибки: </b>';
			$errors = libxml_get_errors();
			foreach ($errors as $error) {
			  print $this->libxml_display_error($error);
			}
			libxml_clear_errors();
		} else {
			return 'Проверка успешно пройдена';
		}
		 echo "<br/><br/><br/><br/><br/><br/><br/>";

	}


/* libXML error catcher. */
	public function libxml_display_error($error) 
	{
		$return = "<br/>\n";
		switch ($error->level) {
			case LIBXML_ERR_WARNING:
				$return .= "<b>Warning $error->code</b>: ";
				break;
			case LIBXML_ERR_ERROR:
				$return .= "<b>Error $error->code</b>: ";
				break;
			case LIBXML_ERR_FATAL:
				$return .= "<b>Fatal Error $error->code</b>: ";
				break;
		}
		$return .= trim($error->message);
		if ($error->file) {
			$return .=    " in <b>$error->file</b>";
		}
		$return .= " on line <b>$error->line</b>\n";
	 
		return $return;
	}
	
	public function xml_attribute($object, $attribute)
	{
		if(isset($object[$attribute]))
			return (string) $object[$attribute];
	}
}
?>