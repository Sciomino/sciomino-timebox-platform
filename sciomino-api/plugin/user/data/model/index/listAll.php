<?

class indexListAll extends control {

    function Run() {

        global $XCOW_B;

	#
	# init
	#
	$indexList = array();

	# knowledge
	$knowledgeList = array();

	# experience
	$companyList = array();
	$eventList = array();
	$educationList = array();
	$productList = array();

	# personal
	$industryList = array();
	$organizationList = array();
	$businessunitList = array();
	$sectionList = array();
	$roleList = array();
	$hometownList = array();
	$workplaceList = array();

	# hobby
	$hobbyList = array();

	# tag
	$tagList = array();

	# list
	$listList = array();
	$publicList = array();
	$managerList = array();

	#
	# get params
	#
	$this->userId = $this->ses['request']['param']['userId'];

        $this->context = $this->ses['request']['param']['context'];
        $this->sub = $this->ses['request']['param']['sub'];
        $this->start = $this->ses['request']['param']['start'];
        if (! isset($this->start)) {$this->start = '';}
	$this->mode = $this->ses['request']['param']['mode'];
	# sort=alpha||num
	$this->sort = $this->ses['request']['param']['sort'];
        if (! isset($this->sort)) {$this->sort = 'alpha';}
       
        $this->order = $this->ses['request']['param']['order'];
        $this->direction = $this->ses['request']['param']['direction'];
        $this->offset = $this->ses['request']['param']['offset'];
        $this->limit = $this->ses['request']['param']['limit'];
        if (! isset($this->limit)) {$this->limit = 500;}

	$this->listSize = 0;

	if ($this->context != '' && $this->mode == "index") {
		# get all indexes from the index with a given context
		# $indexList = SearchIndexListWithContext($this->context);

		$table = "";
		$where = "";
		$order = "";
		$limit = "";
		$expand = 1;

		# get all from the context
		$where .= "(SELECT DISTINCT SearchIndex.ReferenceId FROM SearchIndex, SearchWord WHERE SearchIndex.SearchWordId = SearchWord.SearchWordId AND SearchWordContext = '".safeInsert($this->context)."') AS context";

		# get all with name of a user
		if ($this->start != '') {
			$where .= " INNER JOIN ";
			$where .= "(SELECT DISTINCT SearchIndex.ReferenceId FROM SearchIndex, SearchWord WHERE SearchIndex.SearchWordId = SearchWord.SearchWordId AND SearchWordWord LIKE '".safeInsert($this->start)."%' AND SearchWordContext = 'name') AS name";
			$where .= " USING (ReferenceId) ";
		}

		$indexList = SearchIndexListWithValues($table, $where, $order, $limit, $expand);
		$this->listSize = count($indexList);

	}

	# knowledge
	if ($this->context == "knowledge" && $this->mode != "index") {
		if ($this->mode == "user") {
			$knowledgeContext = SearchWordGetWordsWithReferenceTotalCount('knowledge', $this->userId);
		}
		else {
			$knowledgeContext = SearchWordGetWords('knowledge', $this->start);
		}

		foreach (array_keys($knowledgeContext) as $knKey) {
			$knowledgeList[$knowledgeContext[$knKey]['Word']] = $knowledgeContext[$knKey]['Count'];
		}

		if ($this->sort == "num") {
			arsort($knowledgeList, SORT_NUMERIC);
		}
		else {
			uksort($knowledgeList, "strnatcasecmp");
		}
		$knowledgeList = array_slice($knowledgeList, 0, $this->limit, true);
		$this->listSize = count($knowledgeList);
	}


	# experience
	if ($this->context == "experience" && $this->mode != "index") {

		$companyContext = SearchWordGetWords('Company', $this->start);
		foreach (array_keys($companyContext) as $knKey) {
			$companyList[$companyContext[$knKey]['Word']] = $companyContext[$knKey]['Count'];
		}
		$educationContext = SearchWordGetWords('Education', $this->start);
		foreach (array_keys($educationContext) as $knKey) {
			$educationList[$educationContext[$knKey]['Word']] = $educationContext[$knKey]['Count'];
		}
		$eventContext = SearchWordGetWords('Event', $this->start);
		foreach (array_keys($eventContext) as $knKey) {
			$eventList[$eventContext[$knKey]['Word']] = $eventContext[$knKey]['Count'];
		}
		$productContext = SearchWordGetWords('Product', $this->start);
		foreach (array_keys($productContext) as $knKey) {
			$productList[$productContext[$knKey]['Word']] = $productContext[$knKey]['Count'];
		}

		/*
		$experienceFound = array();
		$sectionList = UserSectionListWithValues('UserExperience', '', '', '', GetSectionProperties('experience'), 1);
		foreach ($sectionList as $section) {
			foreach ($section['annotation'] as $annotation) {
				if ($annotation['name'] == 'subject') {
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
		
		if ($this->sort == "num") {
			arsort($companyList, SORT_NUMERIC);
			arsort($eventList, SORT_NUMERIC);
			arsort($educationList, SORT_NUMERIC);
			arsort($productList, SORT_NUMERIC);
		}
		else {
			uksort($companyList, "strnatcasecmp");
			uksort($eventList, "strnatcasecmp");
			uksort($educationList, "strnatcasecmp");
			uksort($productList, "strnatcasecmp");
		}

		$companyList = array_slice($companyList, 0, $this->limit, true);
		$eventList = array_slice($eventList, 0, $this->limit, true);
		$educationList = array_slice($educationList, 0, $this->limit, true);
		$productList = array_slice($productList, 0, $this->limit, true);
		$this->listSize = count($companyList) + count($eventList) + count($educationList) + count($productList);
	}

	# Product
	if ($this->context == "product" && $this->mode != "index") {
		$searchString = "Product";
		if (isset($this->sub) && $this->sub != '') {
			$searchString .= "-".$this->sub;
		}
		$experienceContext = SearchWordGetWords($searchString, $this->start);

		foreach (array_keys($experienceContext) as $knKey) {
			$productList[$experienceContext[$knKey]['Word']] = $experienceContext[$knKey]['Count'];
		}

		if ($this->sort == "num") {
			arsort($productList, SORT_NUMERIC);
		}
		else {
			uksort($productList, "strnatcasecmp");
		}
		$productList = array_slice($productList, 0, $this->limit, true);
		$this->listSize = count($productList);
	}

	# Company
	if ($this->context == "company" && $this->mode != "index") {
		$searchString = "Company";
		if (isset($this->sub) && $this->sub != '') {
			$searchString .= "-".$this->sub;
		}
		$experienceContext = SearchWordGetWords($searchString, $this->start);

		foreach (array_keys($experienceContext) as $knKey) {
			$companyList[$experienceContext[$knKey]['Word']] = $experienceContext[$knKey]['Count'];
		}

		if ($this->sort == "num") {
			arsort($companyList, SORT_NUMERIC);
		}
		else {
			uksort($companyList, "strnatcasecmp");
		}
		$companyList = array_slice($companyList, 0, $this->limit, true);
		$this->listSize = count($companyList);
	}

	# Education
	if ($this->context == "education" && $this->mode != "index") {
		$searchString = "Education";
		if (isset($this->sub) && $this->sub != '') {
			$searchString .= "-".$this->sub;
		}
		$experienceContext = SearchWordGetWords($searchString, $this->start);

		foreach (array_keys($experienceContext) as $knKey) {
			$educationList[$experienceContext[$knKey]['Word']] = $experienceContext[$knKey]['Count'];
		}

		if ($this->sort == "num") {
			arsort($educationList, SORT_NUMERIC);
		}
		else {
			uksort($educationList, "strnatcasecmp");
		}
		$educationList = array_slice($educationList, 0, $this->limit, true);
		$this->listSize = count($educationList);
	}

	# Event
	if ($this->context == "event" && $this->mode != "index") {
		$searchString = "Event";
		if (isset($this->sub) && $this->sub != '') {
			$searchString .= "-".$this->sub;
		}
		$experienceContext = SearchWordGetWords($searchString, $this->start);

		foreach (array_keys($experienceContext) as $knKey) {
			$eventList[$experienceContext[$knKey]['Word']] = $experienceContext[$knKey]['Count'];
		}

		if ($this->sort == "num") {
			arsort($eventList, SORT_NUMERIC);
		}
		else {
			uksort($eventList, "strnatcasecmp");
		}
		$eventList = array_slice($eventList, 0, $this->limit, true);
		$this->listSize = count($eventList);
	}


	# hobby
	if ($this->context == "hobby" && $this->mode != "index") {
		if ($this->mode == "user") {
			$hobbyContext = SearchWordGetWordsWithReferenceTotalCount('hobby', $this->userId);
		}
		else {
			$hobbyContext = SearchWordGetWords('hobby', $this->start);
		}

		foreach (array_keys($hobbyContext) as $knKey) {
			$hobbyList[$hobbyContext[$knKey]['Word']] = $hobbyContext[$knKey]['Count'];
		}

		if ($this->sort == "num") {
			arsort($hobbyList, SORT_NUMERIC);
		}
		else {
                	uksort($hobbyList, "strnatcasecmp");
		}

		$hobbyList = array_slice($hobbyList, 0, $this->limit, true);
		$this->listSize = count($hobbyList);
	}


	# personal 
	if ($this->context == "industry" && $this->mode != "index") {
		# industry
		$industryContext = SearchWordGetWords('industry', $this->start);

		foreach (array_keys($industryContext) as $peKey) {
			$industryList[$industryContext[$peKey]['Word']] = $industryContext[$peKey]['Count'];
		}

		if ($this->sort == "num") {
			arsort($industryList, SORT_NUMERIC);
		}
		else {
                	uksort($industryList, "strnatcasecmp");
		}
		$industryList = array_slice($industryList, 0, $this->limit, true);
		$this->listSize = count($industryList);
	}
	if ($this->context == "organization" && $this->mode != "index") {
		# organization
		$organizationContext = SearchWordGetWords('organization', $this->start);

		foreach (array_keys($organizationContext) as $peKey) {
			$organizationList[$organizationContext[$peKey]['Word']] = $organizationContext[$peKey]['Count'];
		}

		if ($this->sort == "num") {
			arsort($organizationList, SORT_NUMERIC);
		}
		else {
                	uksort($organizationList, "strnatcasecmp");
		}
		$organizationList = array_slice($organizationList, 0, $this->limit, true);
		$this->listSize = count($organizationList);
	}
	if ($this->context == "businessunit" && $this->mode != "index") {
		# businessunit
		$businessunitContext = SearchWordGetWords('businessunit', $this->start);

		foreach (array_keys($businessunitContext) as $peKey) {
			$businessunitList[$businessunitContext[$peKey]['Word']] = $businessunitContext[$peKey]['Count'];
		}

		if ($this->sort == "num") {
			arsort($businessunitList, SORT_NUMERIC);
		}
		else {
                	uksort($businessunitList, "strnatcasecmp");
		}
		$businessunitList = array_slice($businessunitList, 0, $this->limit, true);
		$this->listSize = count($businessunitList);
	}
	if ($this->context == "section" && $this->mode != "index") {
		# section
		$sectionContext = SearchWordGetWords('section', $this->start);

		foreach (array_keys($sectionContext) as $peKey) {
			$sectionList[$sectionContext[$peKey]['Word']] = $sectionContext[$peKey]['Count'];
		}

		if ($this->sort == "num") {
			arsort($sectionList, SORT_NUMERIC);
		}
		else {
                	uksort($sectionList, "strnatcasecmp");
		}
		$sectionList = array_slice($sectionList, 0, $this->limit, true);
		$this->listSize = count($sectionList);
	}
	if ($this->context == "role" && $this->mode != "index") {
		# role
		$roleContext = SearchWordGetWords('role', $this->start);

		foreach (array_keys($roleContext) as $peKey) {
			$roleList[$roleContext[$peKey]['Word']] = $roleContext[$peKey]['Count'];
		}

		if ($this->sort == "num") {
			arsort($roleList, SORT_NUMERIC);
		}
		else {
                	uksort($roleList, "strnatcasecmp");
		}
		$roleList = array_slice($roleList, 0, $this->limit, true);
		$this->listSize = count($roleList);
	}
	if ($this->context == "hometown" && $this->mode != "index") {
		# hometown
		$hometownContext = SearchWordGetWords('hometown', $this->start);

		foreach (array_keys($hometownContext) as $peKey) {
			$hometownList[$hometownContext[$peKey]['Word']] = $hometownContext[$peKey]['Count'];
		}

		if ($this->sort == "num") {
			arsort($hometownList, SORT_NUMERIC);
		}
		else {
                	uksort($hometownList, "strnatcasecmp");
		}
		$hometownList = array_slice($hometownList, 0, $this->limit, true);
		$this->listSize = count($hometownList);
	}
	if ($this->context == "workplace" && $this->mode != "index") {
		# workplace
		$workplaceContext = SearchWordGetWords('workplace', $this->start);

		foreach (array_keys($workplaceContext) as $peKey) {
			$workplaceList[$workplaceContext[$peKey]['Word']] = $workplaceContext[$peKey]['Count'];
		}

		if ($this->sort == "num") {
			arsort($workplaceList, SORT_NUMERIC);
		}
		else {
                	uksort($workplaceList, "strnatcasecmp");
		}
		$workplaceList = array_slice($workplaceList, 0, $this->limit, true);
		$this->listSize = count($workplaceList);
	}

	# tags
	if ($this->context == "tag" && $this->mode != "index") {
		if ($this->mode == "user") {
			$tagContext = SearchWordGetWordsWithReferenceTotalCount('tag', $this->userId);
		}
		else {
			$tagContext = SearchWordGetWords('tag', $this->start);
		}

		foreach (array_keys($tagContext) as $tagKey) {
			$tagList[$tagContext[$tagKey]['Word']] = $tagContext[$tagKey]['Count'];
		}

		if ($this->sort == "num") {
			arsort($tagList, SORT_NUMERIC);
		}
		else {
                	uksort($tagList, "strnatcasecmp");
		}
		$tagList = array_slice($tagList, 0, $this->limit, true);
		$this->listSize = count($tagList);
	}

	# lists
	if ($this->context == "list" && $this->mode != "index") {
		$groupList = UserGroupListWithValues('UserGroup', 'WHERE UserGroupType = "private" AND UserId = '.$this->userId, '', '', 0);
		foreach ($groupList as $group) {
			$listList[$group['name']] = UserInGroupCount(0,'UserInGroup.UserGroupId = '.$group['id']);
		}

		if ($this->sort == "num") {
			arsort($listList, SORT_NUMERIC);
		}
		else {
                	uksort($listList, "strnatcasecmp");
		}
		$listList = array_slice($listList, 0, $this->limit, true);
		$this->listSize = count($listList);
	}

	# public lists
	if ($this->context == "publicList" && $this->mode != "index") {
		$groupList = UserGroupListWithValues('UserGroup', 'WHERE UserGroupType = "public"', '', '', 0);
		foreach ($groupList as $group) {
			$publicList[$group['name']] = UserInGroupCount(0,'UserInGroup.UserGroupId = '.$group['id']);
		}

		if ($this->sort == "num") {
			arsort($publicList, SORT_NUMERIC);
		}
		else {
                	uksort($publicList, "strnatcasecmp");
		}
		$publicList = array_slice($publicList, 0, $this->limit, true);
		$this->listSize = count($publicList);
	}

	# manager lists
	if ($this->context == "managerList" && $this->mode != "index") {
		$groupList = UserGroupListWithValues('UserGroup', 'WHERE UserGroupType = "manager"', '', '', 0);
		foreach ($groupList as $group) {
			$managerList[$group['name']] = UserInGroupCount(0,'UserInGroup.UserGroupId = '.$group['id']);
		}

		if ($this->sort == "num") {
			arsort($managerList, SORT_NUMERIC);
		}
		else {
                	uksort($managerList, "strnatcasecmp");
		}
		$managerList = array_slice($managerList, 0, $this->limit, true);
		$this->listSize = count($managerList);
	}

	#
	# Summary
	#
	$this->ses['response']['param']['listSize'] = $this->listSize;

	if ($this->offset) {
        	$this->ses['response']['param']['listCursor'] = $this->offset;
	}
	else {
        	$this->ses['response']['param']['listCursor'] = 0;
	}

	#
	# Content
	#
	$this->ses['response']['param']['suggestList'] = array();
	$this->ses['response']['param']['indexList'] = $indexList;

	# knowledge
	$this->ses['response']['param']['knowledgeList'] = $knowledgeList;

	# experience
	$this->ses['response']['param']['companyList'] = $companyList;
	$this->ses['response']['param']['eventList'] = $eventList;
	$this->ses['response']['param']['educationList'] = $educationList;
	$this->ses['response']['param']['productList'] = $productList;

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
