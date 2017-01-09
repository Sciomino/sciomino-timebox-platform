<?

class indexList extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$indexList = array();
	$indexListSize = 0;

	# suggestions
	$suggest = 0;
	$suggestList = array();

	# knowledge
	$knowledgeList = array();
	# detail
	$levelList = array();

	# experience
	$companyList = array();
	$eventList = array();
	$educationList = array();
	$productList = array();
	# detail
	$titleList = array();
	$alternativeList = array();
	$likeList = array();
	$hasList = array();

	# hobbies
	$hobbyList = array();

	# personal
	$industryList = array();
	$organizationList = array();
	$businessunitList = array();
	$sectionList = array();
	$roleList = array();
	$hometownList = array();
	$workplaceList = array();

	# tag
	$tagList = array();

	# list
	$listList = array();
	$publicList = array();
	$managerList = array();

	$table = "";
	$where = "";
	$order = "";
	$limit = "";
	$expand = 1;

	#
	# get params
	#
	$this->userId = $this->ses['request']['param']['userId'];

	$this->mode = $this->ses['request']['param']['mode'];
        if (! isset($this->mode)) {$this->mode = 'none';}

        $this->name = $this->ses['request']['param']['n'];
        $this->query = $this->ses['request']['param']['q'];
        
  	$this->knowledgeList = $this->ses['request']['param']['k'];
  	$this->experienceList = $this->ses['request']['param']['e'];
  	$this->hobbyList = $this->ses['request']['param']['h'];
  	$this->personalList = $this->ses['request']['param']['p'];
  	$this->tagList = $this->ses['request']['param']['t'];
  	$this->listList = $this->ses['request']['param']['l'];
  	$this->typeListList = $this->ses['request']['param']['tl'];

	$this->detail = $this->ses['request']['param']['detail'];
        if (! isset($this->detail)) {$this->detail = 'all';}
	$this->knowledgeDetail = $this->ses['request']['param']['knowledgeDetail'];
	$this->knowledgeLevelDetail = $this->ses['request']['param']['knowledgeLevelDetail'];
	$this->experienceDetail = $this->ses['request']['param']['experienceDetail'];
	$this->experienceTitleDetail = $this->ses['request']['param']['experienceTitleDetail'];
	$this->experienceAlternativeDetail = $this->ses['request']['param']['experienceAlternativeDetail'];
	$this->experienceLikeDetail = $this->ses['request']['param']['experienceLikeDetail'];
	$this->experienceHasDetail = $this->ses['request']['param']['experienceHasDetail'];

        $this->order = $this->ses['request']['param']['order'];
        $this->direction = $this->ses['request']['param']['direction'];
        $this->offset = $this->ses['request']['param']['offset'];
        $this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 500;}

        $this->subLimit = $this->ses['request']['param']['subLimit'];
        if (! isset($this->subLimit)) {$this->subLimit = 5;}
#$time1 = getMicrotime();

	#
	# Contruct TABLE
	#
	$table = "";
	$where = "";
	$join = "";

	#
	# Contruct WHERE
	#
	if ($this->mode == "all") {
		$where .= "(SELECT DISTINCT SearchIndex.ReferenceId FROM SearchIndex) as dummy";
	}

	# search with name
        if (isset ($this->name)) {
		$suggest = 0;
		$words[] = $this->name;

	    if ($where != "") { $where .= " INNER JOIN ";}
		$where .= "(SELECT DISTINCT SearchIndex.ReferenceId FROM SearchIndex, SearchWord WHERE SearchIndex.SearchWordId = SearchWord.SearchWordId AND SearchWordWord LIKE '%".safeInsert($this->name)."%' AND SearchWordContext = 'name') AS name";
	    if ($join == "") { $join = "ReferenceId"; } else { $where .= " USING (".$join.")"; }

        }

	# Search with searchwords
        if (isset($this->query)) {
	 	$suggest = 1;
		# problem with like, with first % the index is not used!
                # $where .= "(SearchWord.SearchWordWord like \"%".$this->query."%\")";
                # $where .= "(SearchWord.SearchWordWord like \"".$this->query."%\")";

		$words = explode(" ", $this->query);
	
		$countWord = 1;
		foreach ($words as $word) {
	                if ($where != "") { $where .= " INNER JOIN "; }
			$where .= "(SELECT DISTINCT SearchIndex.ReferenceId FROM SearchIndex, SearchWord WHERE SearchIndex.SearchWordId = SearchWord.SearchWordId AND SearchWordWord LIKE '".safeInsert($word)."%') AS word".$countWord;
		        if ($join == "") { $join = "ReferenceId"; } else { $where .= " USING (".$join.")"; }
			$countWord++;
		}

        }

	# search with knowledge
        if (isset ($this->knowledgeList)) {
		$suggest = 0;
		$countKnowledge = 1;
		foreach ($this->knowledgeList as $knowledge => $level) {
		        if ($where != "") { $where .= " INNER JOIN "; }
			$where .= "(SELECT DISTINCT SearchIndex.ReferenceId FROM SearchIndex, SearchWord WHERE SearchIndex.SearchWordId = SearchWord.SearchWordId AND SearchWordWord = '".safeInsert($knowledge)."' AND SearchWordContext = 'knowledge') AS knowledge".$countKnowledge;
		        if ($join == "") { $join = "ReferenceId"; } else { $where .= " USING (".$join.")"; }

			if ($level != '') {
			        if ($where != "") { $where .= " INNER JOIN "; }
				// select only id's that match the level of this $KNOWLEDGE
				$where .= "(SELECT DISTINCT UserProfile.UserId FROM UserProfile, UserProfileAnnotation WHERE UserProfile.ProfileId = UserProfileAnnotation.ProfileId AND UserProfileAnnotation.ProfileId in (SELECT DISTINCT UserProfileAnnotation.ProfileId FROM UserProfileAnnotation WHERE UserProfileAnnotation.AnnotationAttribute = 'field' AND UserProfileAnnotation.AnnotationValue = '".safeInsert($knowledge)."') AND UserProfileAnnotation.ProfileId in (SELECT DISTINCT UserProfileAnnotation.ProfileId FROM UserProfileAnnotation WHERE UserProfileAnnotation.AnnotationAttribute = 'level' AND UserProfileAnnotation.AnnotationValue = '".safeInsert($level)."')) AS knowledgeLevel".$countKnowledge;
			        $where .= " ON ReferenceId = knowledgeLevel".$countKnowledge.".UserId";
			}
			$countKnowledge++;
		}
        }

	# search with experience
        if (isset ($this->experienceList)) {
		$suggest = 0;
		$countExperience = 1;
		foreach ($this->experienceList as $type => $rest) {
			foreach ($rest as $experience => $filter) {
			        if ($where != "") { $where .= " INNER JOIN "; }
				$where .= "(SELECT DISTINCT SearchIndex.ReferenceId FROM SearchIndex, SearchWord WHERE SearchIndex.SearchWordId = SearchWord.SearchWordId AND SearchWordWord = '".safeInsert($experience)."' AND SearchWordContext = '".safeInsert($type)."') AS experience".$countExperience;
			        if ($join == "") { $join = "ReferenceId"; } else { $where .= " USING (".$join.")"; }

				list($title, $alternative, $like, $has) = explode(',', $filter);
				$title = urldecode($title);
				$alternative = urldecode($alternative);
				if ($filter != '') {
				        if ($where != "") { $where .= " INNER JOIN "; }
					// select only id's that match the title, alternative, like and has of this $EXPERIENCE
					$where .= "(SELECT DISTINCT UserExperience.UserId FROM UserExperience, UserExperienceAnnotation WHERE UserExperience.SectionId = UserExperienceAnnotation.SectionId AND UserExperienceAnnotation.SectionId in (SELECT DISTINCT UserExperienceAnnotation.SectionId FROM UserExperienceAnnotation WHERE UserExperienceAnnotation.AnnotationAttribute = 'subject' AND UserExperienceAnnotation.AnnotationValue = '".safeInsert($experience)."')";
					if ($title != '') {
						$where .= " AND UserExperienceAnnotation.SectionId in (SELECT DISTINCT UserExperienceAnnotation.SectionId FROM UserExperienceAnnotation WHERE UserExperienceAnnotation.AnnotationAttribute = 'title' AND UserExperienceAnnotation.AnnotationValue = '".safeInsert($title)."')";
					}
					if ($alternative != '') {
						$where .= " AND UserExperienceAnnotation.SectionId in (SELECT DISTINCT UserExperienceAnnotation.SectionId FROM UserExperienceAnnotation WHERE UserExperienceAnnotation.AnnotationAttribute = 'alternative' AND UserExperienceAnnotation.AnnotationValue = '".safeInsert($alternative)."')";
					}
					if ($like != '') {
						$where .= " AND UserExperienceAnnotation.SectionId in (SELECT DISTINCT UserExperienceAnnotation.SectionId FROM UserExperienceAnnotation WHERE UserExperienceAnnotation.AnnotationAttribute = 'like' AND UserExperienceAnnotation.AnnotationValue = '".safeInsert($like)."')";
					}
					if ($has != '') {
						$where .= " AND UserExperienceAnnotation.SectionId in (SELECT DISTINCT UserExperienceAnnotation.SectionId FROM UserExperienceAnnotation WHERE UserExperienceAnnotation.AnnotationAttribute = 'has' AND UserExperienceAnnotation.AnnotationValue = '".safeInsert($has)."')";
					}
					$where .= ") AS experienceFilter".$countExperience;
				        $where .= " ON ReferenceId = experienceFilter".$countExperience.".UserId";
				}
				$countExperience++;
			}
		}
        }

	# search with hobby
        if (isset ($this->hobbyList)) {
		$suggest = 0;
		$countHobby = 1;
		foreach ($this->hobbyList as $hobby => $dummy) {
		        if ($where != "") { $where .= " INNER JOIN "; }
			$where .= "(SELECT DISTINCT SearchIndex.ReferenceId FROM SearchIndex, SearchWord WHERE SearchIndex.SearchWordId = SearchWord.SearchWordId AND SearchWordWord = '".safeInsert($hobby)."' AND SearchWordContext = 'hobby') AS hobby".$countHobby;
		        if ($join == "") { $join = "ReferenceId"; } else { $where .= " USING (".$join.")"; }
			$countHobby++;
		}
        }

	# search with personal
        if (isset ($this->personalList)) {
		$suggest = 0;
		$countPersonal = 1;
		foreach ($this->personalList as $attribute => $value) {
		        if ($where != "") { $where .= " INNER JOIN "; }
			$where .= "(SELECT DISTINCT SearchIndex.ReferenceId FROM SearchIndex, SearchWord WHERE SearchIndex.SearchWordId = SearchWord.SearchWordId AND SearchWordWord = '".safeInsert($value)."' AND SearchWordContext = '".safeInsert($attribute)."') AS personal".$countPersonal;
		        if ($join == "") { $join = "ReferenceId"; } else { $where .= " USING (".$join.")"; }
			$countPersonal++;
		}
        }

	# search with tag
        if (isset ($this->tagList)) {
		$suggest = 0;
		$countTag = 1;
		foreach ($this->tagList as $tag => $dummy) {
		        if ($where != "") { $where .= " INNER JOIN "; }
			$where .= "(SELECT DISTINCT SearchIndex.ReferenceId FROM SearchIndex, SearchWord WHERE SearchIndex.SearchWordId = SearchWord.SearchWordId AND SearchWordWord = '".safeInsert($tag)."' AND SearchWordContext = 'tag') AS tag".$countTag;
		        if ($join == "") { $join = "ReferenceId"; } else { $where .= " USING (".$join.")"; }
			$countTag++;
		}
        }

		# search with list
        if (isset ($this->listList)) {
		$suggest = 0;
		$countList = 1;
		foreach ($this->listList as $list => $dummy) {
		        if ($where != "") { $where .= " INNER JOIN "; } else { $where .= "(SELECT DISTINCT SearchIndex.ReferenceId FROM SearchIndex) AS dummy INNER JOIN "; }
			$where .= "(SELECT DISTINCT UserInGroup.UserId FROM UserInGroup, UserGroup WHERE UserInGroup.UserGroupId = UserGroup.UserGroupId AND UserGroup.UserGroupName = '".safeInsert($list)."' AND UserGroup.UserId = $this->userId) AS list".$countList;
		        if ($join == "") { $join = "ReferenceId"; $where .= " ON ".$join." = list".$countList.".UserId"; } else { $where .= " ON ".$join." = list".$countList.".UserId"; }
			$countList++;
		}
        }

		# search with types list
        if (isset ($this->typeListList)) {
		$suggest = 0;
		$countList = 1;
			foreach ($this->typeListList as $type => $list) {
		        if ($where != "") { $where .= " INNER JOIN "; } else { $where .= "(SELECT DISTINCT SearchIndex.ReferenceId FROM SearchIndex) AS dummy INNER JOIN "; }
				$where .= "(SELECT DISTINCT UserInGroup.UserId FROM UserInGroup, UserGroup WHERE UserInGroup.UserGroupId = UserGroup.UserGroupId AND UserGroup.UserGroupName = '".safeInsert($list)."' AND UserGroup.UserGroupType = '".safeInsert($type)."') AS tlist".$countList;
		        if ($join == "") { $join = "ReferenceId"; $where .= " ON ".$join." = tlist".$countList.".UserId"; } else { $where .= " ON ".$join." = tlist".$countList.".UserId"; }
				$countList++;
			}
        }

	#
	# Contruct ORDER
	#
        if (isset($this->order)) {
		$fix_for_same_entry = 0;
                $order .= "ORDER BY ";

		#
		# options: id|lastname
		#
		switch ($this->order) {
			case "id":
               			$order .= "ReferenceId";
				break;
			case "lastname":
               			$order .= "(SELECT UserLastName FROM User WHERE ReferenceId = User.UserId)";
				$fix_for_same_entry = 1;
				break;
			default:
				$order .= "ReferenceId";
		}

        	if (isset($this->direction)) {
                	$order .= " ";
                	$order .= "$this->direction";
        	}

		if ($fix_for_same_entry) {
               		$order .= ", ReferenceId";
        		if (isset($this->direction)) {
                		$order .= " ";
                		$order .= "$this->direction";
        		}
		}
        }
	
	#
	# Contruct LIMIT
	#
        if (isset($this->limit)) {
                $limit .= "LIMIT ";
                $limit .= "$this->limit";

        	if (isset($this->offset)) {
                	$limit .= " OFFSET ";
                	$limit .= "$this->offset";
        	}
        }
	#
        # Get List
        #
        if ($where != "") {
			$indexList = SearchIndexListWithValues($table, $where, $order, $limit, $expand);
			$indexListSize = MysqlCountWithValues("DISTINCT ReferenceId", $table, $where, $order);
		}

	$indexString = implode(",",$indexList);

#$time2 = getMicrotime();

	#
	# suggestions
	#
	if ($suggest) {
		// exact match first
		$exactMatch = array($this->query);
		$suggestList = SearchWordGetContext($exactMatch, "'name','knowledge','hobby','tag','hometown','workplace','role','section','businessunit','organization','industry','Company','Product','Education','Event'");
		// else, try to match words
		if ( count($suggestList) == 0 ) {
			$suggestList = SearchWordGetContext($words, "'name','knowledge','hobby','tag','hometown','workplace','role','section','businessunit','organization','industry','Company','Product','Education','Event'");
		}
		
		$suggestList = array_slice($suggestList, 0, $this->subLimit, true);
	}

	#
	# get knowledgefields AND experiences
	# - OR get knowledge details 
	# - OR get experience details
	#	

	# knowledgefields
	// remove the query keys from the list
	$knowledgeKeys = array();
	if (isset ($this->knowledgeList)) {
		$knowledgeKeys = array_keys($this->knowledgeList);
	}

	if ($indexString != '' && ( $this->detail == "all" || $this->detail == "knowledgeOnly") ) {

		$knowledgeContext = SearchWordGetWordsWithReference('knowledge', $indexString);

		foreach (array_keys($knowledgeContext) as $knKey) {
			if (! in_array($knowledgeContext[$knKey]['Word'], $knowledgeKeys)) {
				$knowledgeList[$knowledgeContext[$knKey]['Word']] = $knowledgeContext[$knKey]['Count'];
			}
		}

		arsort($knowledgeList, SORT_NUMERIC);
		if ($this->detail == "all") {
			$knowledgeList = array_slice($knowledgeList, 0, $this->subLimit, true);
		}
	}

	# knowledge details
	if ($indexString != '' && $this->detail == "knowledge") {
		# construct WHERE
		$where = "WHERE UserId in ($indexString) AND ProfileGroup = 'knowledgefield'";

		if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
		$where .= "UserProfile.ProfileId in (SELECT DISTINCT UserProfile.ProfileId FROM UserProfile, UserProfileAnnotation WHERE UserProfile.ProfileId = UserProfileAnnotation.ProfileId AND UserProfileAnnotation.ProfileId in (SELECT DISTINCT UserProfileAnnotation.ProfileId FROM UserProfileAnnotation WHERE UserProfileAnnotation.AnnotationAttribute = 'field' AND UserProfileAnnotation.AnnotationValue = '".safeInsert($this->knowledgeDetail)."')";

		# level is optional
		if ($this->knowledgeLevelDetail != '') {
			$where .= " AND UserProfileAnnotation.ProfileId in (SELECT DISTINCT UserProfileAnnotation.ProfileId FROM UserProfileAnnotation WHERE UserProfileAnnotation.AnnotationAttribute = 'level' AND UserProfileAnnotation.AnnotationValue = '".safeInsert($this->knowledgeLevelDetail)."')";
		}

		$where .= ") GROUP BY UserProfile.UserId";

		$profileList = UserProfileListWithValues('UserProfile', $where, '', '', GetProfileProperties('user'), 1);

		foreach ($profileList as $profile) {
			foreach ($profile['annotation'] as $annotation) {
				if ($annotation['name'] == 'level') {
					if (array_key_exists($annotation['value'], $levelList)) {
						$levelList[$annotation['value']] = $levelList[$annotation['value']] + 1;
					}
					else {
						$levelList[$annotation['value']] = 1;
					}
				}

			}
		}
		arsort($levelList, SORT_NUMERIC);
	}

	# experiences
	// remove the query keys from the list
	$experienceKeys = array();
	if (isset ($this->experienceList)) {
		foreach ($this->experienceList as $type => $rest) {
			foreach (array_keys($rest) as $item) {
				$experienceKeys[] = $type.$item;
			}
		}
	}
	if ($indexString != '' && ( $this->detail == "all" || $this->detail == "experienceOnly") ) {

		$companyContext = SearchWordGetWordsWithReference('Company', $indexString);
		foreach (array_keys($companyContext) as $knKey) {
			if (! in_array("Company".$companyContext[$knKey]['Word'], $experienceKeys)) {
				$companyList[$companyContext[$knKey]['Word']] = $companyContext[$knKey]['Count'];
			}
		}
		$educationContext = SearchWordGetWordsWithReference('Education', $indexString);
		foreach (array_keys($educationContext) as $knKey) {
			if (! in_array("Education".$educationContext[$knKey]['Word'], $experienceKeys)) {
				$educationList[$educationContext[$knKey]['Word']] = $educationContext[$knKey]['Count'];
			}
		}
		$eventContext = SearchWordGetWordsWithReference('Event', $indexString);
		foreach (array_keys($eventContext) as $knKey) {
			if (! in_array("Event".$eventContext[$knKey]['Word'], $experienceKeys)) {
				$eventList[$eventContext[$knKey]['Word']] = $eventContext[$knKey]['Count'];
			}
		}
		$productContext = SearchWordGetWordsWithReference('Product', $indexString);
		foreach (array_keys($productContext) as $knKey) {
			if (! in_array("Product".$productContext[$knKey]['Word'], $experienceKeys)) {
				$productList[$productContext[$knKey]['Word']] = $productContext[$knKey]['Count'];
			}
		}

		/*
		$experienceFound = array();
		$userSectionList = UserSectionListWithValues('UserExperience', 'WHERE UserExperience.UserId in ('.$indexString.')', '', '', GetSectionProperties('experience'), 1);
		foreach ($userSectionList as $section) {
			foreach ($section['annotation'] as $annotation) {
				if (trim($annotation['value']) != "" && $annotation['name'] == 'subject' && ! in_array($section['name'].$annotation['value'], $experienceKeys) ) {
					// users can have more experiences of the same type, only count one of them
					if (! in_array($section['userId'].$section['name'].$annotation['name'].$annotation['value'], $experienceFound)) {
						$experienceFound[] = $section['userId'].$section['name'].$annotation['name'].$annotation['value'];
						switch ($section['name']) {
							case "Company":
								if (array_key_exists($annotation['value'], $companyList)) {
									$companyList[$annotation['value']] = $companyList[$annotation['value']] + 1;
								}
								else {
									$companyList[$annotation['value']] = 1;
								}
								break;
							case "Event":
								if (array_key_exists($annotation['value'], $eventList)) {
									$eventList[$annotation['value']] = $eventList[$annotation['value']] + 1;
								}
								else {
									$eventList[$annotation['value']] = 1;
								}
								break;
							case "Education":
								if (array_key_exists($annotation['value'], $educationList)) {
									$educationList[$annotation['value']] = $educationList[$annotation['value']] + 1;
								}
								else {
									$educationList[$annotation['value']] = 1;
								}
								break;
							case "Product":
								if (array_key_exists($annotation['value'], $productList)) {
									$productList[$annotation['value']] = $productList[$annotation['value']] + 1;
								}
								else {
									$productList[$annotation['value']] = 1;
								}
								break;
						}
					}
				}
			}
		}
		*/
		
		arsort($companyList, SORT_NUMERIC);
		arsort($eventList, SORT_NUMERIC);
		arsort($educationList, SORT_NUMERIC);
		arsort($productList, SORT_NUMERIC);
		if ($this->detail == "all") {
			$companyList = array_slice($companyList, 0, $this->subLimit, true);
			$eventList = array_slice($eventList, 0, $this->subLimit, true);
			$educationList = array_slice($educationList, 0, $this->subLimit, true);
			$productList = array_slice($productList, 0, $this->subLimit, true);
		}
	}

	# experience details
	if ($indexString != '' && $this->detail == "experience") {
		$experienceFound = array();

		# construct WHERE
		$where = "WHERE UserExperience.UserId in ($indexString)";

		# select only id's that match the type, subject, title, alternative, like and has of this $EXPERIENCE
		# - type & subject is compulsary
		list($typeDetail, $subjectDetail) = explode(',', $this->experienceDetail, 2);

		if ($where != "") { $where .= " AND "; } else { $where .= "WHERE "; }
		$where .= "UserExperience.SectionId in (SELECT DISTINCT UserExperience.SectionId FROM UserExperience, UserExperienceAnnotation WHERE UserExperience.SectionName = '".safeInsert($typeDetail)."' AND UserExperience.SectionId = UserExperienceAnnotation.SectionId AND UserExperienceAnnotation.SectionId in (SELECT DISTINCT UserExperienceAnnotation.SectionId FROM UserExperienceAnnotation WHERE UserExperienceAnnotation.AnnotationAttribute = 'subject' AND UserExperienceAnnotation.AnnotationValue = '".safeInsert($subjectDetail)."')";

		# - title, alternative, like and has are optional
		if ($this->experienceTitleDetail != '') {
			$where .= " AND UserExperienceAnnotation.SectionId in (SELECT DISTINCT UserExperienceAnnotation.SectionId FROM UserExperienceAnnotation WHERE UserExperienceAnnotation.AnnotationAttribute = 'title' AND UserExperienceAnnotation.AnnotationValue = '".safeInsert($this->experienceTitleDetail)."')";
		}
		if ($this->experienceAlternativeDetail != '') {
			$where .= " AND UserExperienceAnnotation.SectionId in (SELECT DISTINCT UserExperienceAnnotation.SectionId FROM UserExperienceAnnotation WHERE UserExperienceAnnotation.AnnotationAttribute = 'alternative' AND UserExperienceAnnotation.AnnotationValue = '".safeInsert($this->experienceAlternativeDetail)."')";
		}
		if ($this->experienceLikeDetail != '') {
			$where .= " AND UserExperienceAnnotation.SectionId in (SELECT DISTINCT UserExperienceAnnotation.SectionId FROM UserExperienceAnnotation WHERE UserExperienceAnnotation.AnnotationAttribute = 'like' AND UserExperienceAnnotation.AnnotationValue = '".safeInsert($this->experienceLikeDetail)."')";
		}
		if ($this->experienceHasDetail != '') {
			$where .= " AND UserExperienceAnnotation.SectionId in (SELECT DISTINCT UserExperienceAnnotation.SectionId FROM UserExperienceAnnotation WHERE UserExperienceAnnotation.AnnotationAttribute = 'has' AND UserExperienceAnnotation.AnnotationValue = '".safeInsert($this->experienceHasDetail)."')";
		}

		$where .= ")";
#echo "$where";
		$userSectionList = UserSectionListWithValues('UserExperience', $where, '', '', GetSectionProperties('experience'), 1);
		foreach ($userSectionList as $section) {
			foreach ($section['annotation'] as $annotation) {
				// users can have more experiences of the same type, only count one of them
				if (! in_array($section['userId'].$section['name'].$annotation['name'].$annotation['value'], $experienceFound)) {
					$experienceFound[] = $section['userId'].$section['name'].$annotation['name'].$annotation['value'];
					switch ($annotation['name']) {
						case 'title':
							if (array_key_exists($annotation['value'], $titleList)) {
								$titleList[$annotation['value']] = $titleList[$annotation['value']] + 1;
							}
							else {
								$titleList[$annotation['value']] = 1;
							}
							break;
						case 'alternative':
							if (array_key_exists($annotation['value'], $alternativeList)) {
								$alternativeList[$annotation['value']] = $alternativeList[$annotation['value']] + 1;
							}
							else {
								$alternativeList[$annotation['value']] = 1;
							}
							break;
						case 'like':
							if (array_key_exists($annotation['value'], $likeList)) {
								$likeList[$annotation['value']] = $likeList[$annotation['value']] + 1;
							}
							else {
								$likeList[$annotation['value']] = 1;
							}
							break;
						case 'has':
							if (array_key_exists($annotation['value'], $hasList)) {
								$hasList[$annotation['value']] = $hasList[$annotation['value']] + 1;
							}
							else {
								$hasList[$annotation['value']] = 1;
							}
							break;
					}
				}
			}
		}
		arsort($titleList, SORT_NUMERIC);
		arsort($alternativeList, SORT_NUMERIC);
		arsort($likeList, SORT_NUMERIC);
		arsort($hasList, SORT_NUMERIC);
	}

	# hobbies
	// remove the query keys from the list
	$hobbyKeys = array();
	if (isset ($this->hobbyList)) {
		$hobbyKeys = array_keys($this->hobbyList);
	}

	if ($indexString != '' && ( $this->detail == "all" || $this->detail == "hobbyOnly") ) {
		$hobbyContext = SearchWordGetWordsWithReference('hobby', $indexString);

		foreach (array_keys($hobbyContext) as $knKey) {
			if (! in_array($hobbyContext[$knKey]['Word'], $hobbyKeys)) {
				$hobbyList[$hobbyContext[$knKey]['Word']] = $hobbyContext[$knKey]['Count'];
			}
		}

		arsort($hobbyList, SORT_NUMERIC);
		if ($this->detail == "all") {
			$hobbyList = array_slice($hobbyList, 0, $this->subLimit, true);
		}
	}

	# personal 
	// remove the query keys from the list
	$personalKeys = array();
	if (isset ($this->personalList)) {
		$personalKeys = array_keys($this->personalList);
	}
	if ($indexString != '' && ( $this->detail == "all" || $this->detail == "industryOnly") ) {
		# industry
		$industryContext = SearchWordGetWordsWithReference('industry', $indexString);

		foreach (array_keys($industryContext) as $peKey) {
			if (! in_array('industry', $personalKeys)) {
				$industryList[$industryContext[$peKey]['Word']] = $industryContext[$peKey]['Count'];
			}
		}

		arsort($industryList, SORT_NUMERIC);
		if ($this->detail == "all") {
			$industryList = array_slice($industryList, 0, $this->subLimit, true);
		}
	}
	if ($indexString != '' && ( $this->detail == "all" || $this->detail == "organizationOnly") ) {
		# organization
		$organizationContext = SearchWordGetWordsWithReference('organization', $indexString);

		foreach (array_keys($organizationContext) as $peKey) {
			if (! in_array('organization', $personalKeys)) {
				$organizationList[$organizationContext[$peKey]['Word']] = $organizationContext[$peKey]['Count'];
			}
		}

		arsort($organizationList, SORT_NUMERIC);
		if ($this->detail == "all") {
			$organizationList = array_slice($organizationList, 0, $this->subLimit, true);
		}
	}
	if ($indexString != '' && ( $this->detail == "all" || $this->detail == "businessunitOnly") ) {
		# businessunit
		$businessunitContext = SearchWordGetWordsWithReference('businessunit', $indexString);

		foreach (array_keys($businessunitContext) as $peKey) {
			if (! in_array('businessunit', $personalKeys)) {
				$businessunitList[$businessunitContext[$peKey]['Word']] = $businessunitContext[$peKey]['Count'];
			}
		}

		arsort($businessunitList, SORT_NUMERIC);
		if ($this->detail == "all") {
			$businessunitList = array_slice($businessunitList, 0, $this->subLimit, true);
		}
	}
	if ($indexString != '' && ( $this->detail == "all" || $this->detail == "sectionOnly") ) {
		# section
		$sectionContext = SearchWordGetWordsWithReference('section', $indexString);

		foreach (array_keys($sectionContext) as $peKey) {
			if (! in_array('section', $personalKeys)) {
				$sectionList[$sectionContext[$peKey]['Word']] = $sectionContext[$peKey]['Count'];
			}
		}

		arsort($sectionList, SORT_NUMERIC);
		if ($this->detail == "all") {
			$sectionList = array_slice($sectionList, 0, $this->subLimit, true);
		}
	}

	if ($indexString != '' && ( $this->detail == "all" || $this->detail == "roleOnly") ) {
		# role
		$roleContext = SearchWordGetWordsWithReference('role', $indexString);

		foreach (array_keys($roleContext) as $peKey) {
			if (! in_array('role', $personalKeys)) {
				$roleList[$roleContext[$peKey]['Word']] = $roleContext[$peKey]['Count'];
			}
		}
		
		arsort($roleList, SORT_NUMERIC);
		if ($this->detail == "all") {
			$roleList = array_slice($roleList, 0, $this->subLimit, true);
		}
	}

	if ($indexString != '' && ( $this->detail == "all" || $this->detail == "hometownOnly") ) {
		# hometown
		$hometownContext = SearchWordGetWordsWithReference('hometown', $indexString);

		foreach (array_keys($hometownContext) as $peKey) {
			if (! in_array('hometown', $personalKeys)) {
				$hometownList[$hometownContext[$peKey]['Word']] = $hometownContext[$peKey]['Count'];
			}
		}

		arsort($hometownList, SORT_NUMERIC);
		if ($this->detail == "all") {
			$hometownList = array_slice($hometownList, 0, $this->subLimit, true);
		}
	}

	if ($indexString != '' && ( $this->detail == "all" || $this->detail == "workplaceOnly") ) {
		# workplace
		$workplaceContext = SearchWordGetWordsWithReference('workplace', $indexString);

		foreach (array_keys($workplaceContext) as $peKey) {
			if (! in_array('workplace', $personalKeys)) {
				$workplaceList[$workplaceContext[$peKey]['Word']] = $workplaceContext[$peKey]['Count'];
			}
		}

		arsort($workplaceList, SORT_NUMERIC);
		if ($this->detail == "all") {
			$workplaceList = array_slice($workplaceList, 0, $this->subLimit, true);
		}
	}

	# tags
	// remove the query keys from the list
	$tagKeys = array();
	if (isset ($this->tagList)) {
		$tagKeys = array_keys($this->tagList);
	}
	if ($indexString != '' && ( $this->detail == "all" || $this->detail == "tagOnly") ) {

		$tagContext = SearchWordGetWordsWithReference('tag', $indexString);

		foreach (array_keys($tagContext) as $tagKey) {
			if (! in_array($tagContext[$tagKey]['Word'], $tagKeys)) {
				$tagList[$tagContext[$tagKey]['Word']] = $tagContext[$tagKey]['Count'];
			}
		}

		arsort($tagList, SORT_NUMERIC);
		if ($this->detail == "all") {
			$tagList = array_slice($tagList, 0, $this->subLimit, true);
		}
	}

	# lists
	// remove the query keys from the list
	$listKeys = array();
	if (isset ($this->listList)) {
		$listKeys = array_keys($this->listList);
	}
	if ($indexString != '' && ( $this->detail == "all" || $this->detail == "listOnly") ) {
		$groupList = UserGroupListWithValues('UserGroup', 'WHERE UserGroupType = "private" AND UserId = '.$this->userId, '', '', 0);
		foreach ($groupList as $group) {
			if ( ! in_array($group['name'], $listKeys) ) {
				$count = UserInGroupCount(0,'UserInGroup.UserGroupId = '.$group['id'].' AND UserInGroup.UserId in ('.$indexString.')');
				if ($count > 0) {
					$listList[$group['name']] = $count;
				}
			}
		}
		arsort($listList, SORT_NUMERIC);
		if ($this->detail == "all") {
			$listList = array_slice($listList, 0, $this->subLimit, true);
		}
	}

	# types lists
	// remove the query keys from the list
	$tlistKeys = array();
	if (isset ($this->typeListList)) {
		$tlistKeys = array_keys($this->typeListList);
	}
	# public list
	if ($indexString != '' && ( $this->detail == "all" || $this->detail == "publicListOnly") ) {
		if ( ! in_array('public', $tlistKeys) ) {
			$groupList = UserGroupListWithValues('UserGroup', 'WHERE UserGroupType = "public"', '', '', 0);
			foreach ($groupList as $group) {
					$count = UserInGroupCount(0,'UserInGroup.UserGroupId = '.$group['id'].' AND UserInGroup.UserId in ('.$indexString.')');
					if ($count > 0) {
						$publicList[$group['name']] = $count;
					}
			}
		}
		arsort($publicList, SORT_NUMERIC);
		if ($this->detail == "all") {
			$publicList = array_slice($publicList, 0, $this->subLimit, true);
		}
	}
	# manager list
	if ($indexString != '' && ( $this->detail == "all" || $this->detail == "managerListOnly") ) {
		if ( ! in_array('manager', $tlistKeys) ) {
			$groupList = UserGroupListWithValues('UserGroup', 'WHERE UserGroupType = "manager"', '', '', 0);
			foreach ($groupList as $group) {
					$count = UserInGroupCount(0,'UserInGroup.UserGroupId = '.$group['id'].' AND UserInGroup.UserId in ('.$indexString.')');
					if ($count > 0) {
						$managerList[$group['name']] = $count;
					}
			}
		}
		arsort($managerList, SORT_NUMERIC);
		if ($this->detail == "all") {
			$managerList = array_slice($managerList, 0, $this->subLimit, true);
		}
	}

#$time3 = getMicrotime();

	#
	# Summary
	#
	$this->ses['response']['param']['listSize'] = $indexListSize;

	if ($this->offset) {
        	$this->ses['response']['param']['listCursor'] = $this->offset;
	}
	else {
        	$this->ses['response']['param']['listCursor'] = 0;
	}

#$time4 = getMicrotime();
#log2file("****total search time:".($time4 - $time1)."*****index:".($time2 - $time1)."*****filter:".($time3 - $time2)."*****summary:".($time4 - $time3)."*****");

	#
	# Content
	#
	$this->ses['response']['param']['indexList'] = $indexList;

	# suggestion
	$this->ses['response']['param']['suggest'] = $suggest;
	$this->ses['response']['param']['suggestList'] = $suggestList;

	# knowledge
	$this->ses['response']['param']['knowledgeList'] = $knowledgeList;
	# detail
	$this->ses['response']['param']['levelList'] = $levelList;

	# experience
	$this->ses['response']['param']['companyList'] = $companyList;
	$this->ses['response']['param']['eventList'] = $eventList;
	$this->ses['response']['param']['educationList'] = $educationList;
	$this->ses['response']['param']['productList'] = $productList;
	# detail
	$this->ses['response']['param']['titleList'] = $titleList;
	$this->ses['response']['param']['alternativeList'] = $alternativeList;
	$this->ses['response']['param']['likeList'] = $likeList;
	$this->ses['response']['param']['hasList'] = $hasList;

	# hobby
	$this->ses['response']['param']['hobbyList'] = $hobbyList;

	# personal
	$this->ses['response']['param']['industryList'] = $industryList;
	$this->ses['response']['param']['organizationList'] = $organizationList;
	$this->ses['response']['param']['businessunitList'] = $businessunitList;
	$this->ses['response']['param']['sectionList'] = $sectionList;
	$this->ses['response']['param']['roleList'] = $roleList;
	$this->ses['response']['param']['hometownList'] = $hometownList;
	$this->ses['response']['param']['workplaceList'] = $workplaceList;

	# tags
	$this->ses['response']['param']['tagList'] = $tagList;

	# list
	$this->ses['response']['param']['listList'] = $listList;
	$this->ses['response']['param']['publicList'] = $publicList;
	$this->ses['response']['param']['managerList'] = $managerList;
    }

    function GetHeader() {

        $this->ses['response']['header'] = header ("Content-Type: text/xml\n\n");
        return ($this->ses['response']['header']);

    }

}

?>
