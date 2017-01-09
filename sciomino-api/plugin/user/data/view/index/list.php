<?php
 echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
?>
<Response>

 <Header>
  <Status><?php echo $session['response']['stats']['status'] ?></Status>
  <Request><?php echo $session['response']['stats']['request'] ?></Request>
  <ResponseDate><?php echo $session['response']['stats']['date'] ?></ResponseDate>
  <ResponseTime><?php echo $session['response']['stats']['time'] ?></ResponseTime>
 </Header>

 <Content> 
  <Users>
<?php
foreach ($session['response']['param']['indexList'] as $index) {
  	echo "   <User>".$index."</User>\n";
}
?>
  </Users>
  <Suggest>
<?php
foreach ($session['response']['param']['suggestList'] as $suggestVal) {
   	echo "   <entry>\n";
   	echo "    <word>".xmlTokens($suggestVal['Word'])."</word>\n";
   	echo "    <context>".$suggestVal['Context']."</context>\n";
   	echo "   </entry>\n";

}
?>
  </Suggest>
  <Knowledge>
<?php
foreach ($session['response']['param']['knowledgeList'] as $knowledgeKey => $knowledgeVal) {
   	echo "   <entry>\n";
   	echo "    <name>".xmlTokens($knowledgeKey)."</name>\n";
   	echo "    <count>".$knowledgeVal."</count>\n";
   	echo "   </entry>\n";

}
?>
  </Knowledge>
  <Company>
<?php
foreach ($session['response']['param']['companyList'] as $companyKey => $companyVal) {
   	echo "   <entry>\n";
   	echo "    <name>".xmlTokens($companyKey)."</name>\n";
   	echo "    <count>".$companyVal."</count>\n";
   	echo "   </entry>\n";
}
?>
  </Company>
  <Event>
<?php
foreach ($session['response']['param']['eventList'] as $eventKey => $eventVal) {
   	echo "   <entry>\n";
   	echo "    <name>".xmlTokens($eventKey)."</name>\n";
   	echo "    <count>".$eventVal."</count>\n";
   	echo "   </entry>\n";
}
?>
  </Event>
  <Education>
<?php
foreach ($session['response']['param']['educationList'] as $educationKey => $educationVal) {
   	echo "   <entry>\n";
   	echo "    <name>".xmlTokens($educationKey)."</name>\n";
   	echo "    <count>".$educationVal."</count>\n";
   	echo "   </entry>\n";
}
?>
  </Education>
  <Product>
<?php
foreach ($session['response']['param']['productList'] as $productKey => $productVal) {
   	echo "   <entry>\n";
   	echo "    <name>".xmlTokens($productKey)."</name>\n";
   	echo "    <count>".$productVal."</count>\n";
   	echo "   </entry>\n";
}
?>
  </Product>
  <Hobby>
<?php
foreach ($session['response']['param']['hobbyList'] as $hobbyKey => $hobbyVal) {
   	echo "   <entry>\n";
   	echo "    <name>".xmlTokens($hobbyKey)."</name>\n";
   	echo "    <count>".$hobbyVal."</count>\n";
   	echo "   </entry>\n";

}
?>
  </Hobby>
  <Industry>
<?php
foreach ($session['response']['param']['industryList'] as $industryKey => $industryVal) {
   	echo "   <entry>\n";
   	echo "    <name>".xmlTokens($industryKey)."</name>\n";
   	echo "    <count>".$industryVal."</count>\n";
   	echo "   </entry>\n";
}
?>
  </Industry>
  <Organization>
<?php
foreach ($session['response']['param']['organizationList'] as $organizationKey => $organizationVal) {
   	echo "   <entry>\n";
   	echo "    <name>".xmlTokens($organizationKey)."</name>\n";
   	echo "    <count>".$organizationVal."</count>\n";
   	echo "   </entry>\n";
}
?>
  </Organization>
  <Businessunit>
<?php
foreach ($session['response']['param']['businessunitList'] as $businessunitKey => $businessunitVal) {
   	echo "   <entry>\n";
   	echo "    <name>".xmlTokens($businessunitKey)."</name>\n";
   	echo "    <count>".$businessunitVal."</count>\n";
   	echo "   </entry>\n";
}
?>
  </Businessunit>
  <Section>
<?php
foreach ($session['response']['param']['sectionList'] as $sectionKey => $sectionVal) {
   	echo "   <entry>\n";
   	echo "    <name>".xmlTokens($sectionKey)."</name>\n";
   	echo "    <count>".$sectionVal."</count>\n";
   	echo "   </entry>\n";
}
?>
  </Section>
  <Role>
<?php
foreach ($session['response']['param']['roleList'] as $roleKey => $roleVal) {
   	echo "   <entry>\n";
   	echo "    <name>".xmlTokens($roleKey)."</name>\n";
   	echo "    <count>".$roleVal."</count>\n";
   	echo "   </entry>\n";
}
?>
  </Role>
  <Hometown>
<?php
foreach ($session['response']['param']['hometownList'] as $hometownKey => $hometownVal) {
   	echo "   <entry>\n";
   	echo "    <name>".xmlTokens($hometownKey)."</name>\n";
   	echo "    <count>".$hometownVal."</count>\n";
   	echo "   </entry>\n";
}
?>
  </Hometown>
  <Workplace>
<?php
foreach ($session['response']['param']['workplaceList'] as $workplaceKey => $workplaceVal) {
   	echo "   <entry>\n";
   	echo "    <name>".xmlTokens($workplaceKey)."</name>\n";
   	echo "    <count>".$workplaceVal."</count>\n";
   	echo "   </entry>\n";
}
?>
  </Workplace>
  <Tag>
<?php
foreach ($session['response']['param']['tagList'] as $tagKey => $tagVal) {
   	echo "   <entry>\n";
   	echo "    <name>".xmlTokens($tagKey)."</name>\n";
   	echo "    <count>".$tagVal."</count>\n";
   	echo "   </entry>\n";
}
?>
  </Tag>
  <List>
<?php
foreach ($session['response']['param']['listList'] as $listKey => $listVal) {
   	echo "   <entry>\n";
   	echo "    <name>".xmlTokens($listKey)."</name>\n";
   	echo "    <count>".$listVal."</count>\n";
   	echo "   </entry>\n";
}
?>
  </List>
  <PublicList>
<?php
foreach ($session['response']['param']['publicList'] as $plistKey => $plistVal) {
   	echo "   <entry>\n";
   	echo "    <name>".xmlTokens($plistKey)."</name>\n";
   	echo "    <count>".$plistVal."</count>\n";
   	echo "   </entry>\n";
}
?>
  </PublicList>
  <ManagerList>
<?php
foreach ($session['response']['param']['managerList'] as $mlistKey => $mlistVal) {
   	echo "   <entry>\n";
   	echo "    <name>".xmlTokens($mlistKey)."</name>\n";
   	echo "    <count>".$mlistVal."</count>\n";
   	echo "   </entry>\n";
}
?>
  </ManagerList>
  <Summary>
   <CompleteListSize><?php echo $session['response']['param']['listSize'] ?></CompleteListSize>
   <Cursor><?php echo $session['response']['param']['listCursor'] ?></Cursor>
  </Summary> 
 </Content>

</Response>
