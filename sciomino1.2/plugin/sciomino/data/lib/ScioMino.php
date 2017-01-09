<?php

//////////
// User: Contact
//////////

// contact = contact section

function ScioMinoApiUpdateContact($contact, $userId) {

	return UserApiUpdateContactAnnotationListByUser($contact, $userId);

}

function ScioMinoApiListContact ($userId) {

	return UserApiListContactByUserSorted($userId, "Name");

}

//////////
// User: Address
//////////

// address = address section

function ScioMinoApiUpdateAddress($address, $userId) {

	return UserApiUpdateAddressAnnotationListByUser($address, $userId);

}

function ScioMinoApiListAddress ($userId) {

	return UserApiListAddressByUserSorted($userId, "Name");

}

//////////
// User: Organization
//////////

// organization = organization section

function ScioMinoApiUpdateOrganization($organization, $userId) {

	return UserApiUpdateOrganizationAnnotationListByUser($organization, $userId);

}

function ScioMinoApiListOrganization ($userId) {

	return UserApiListOrganizationByUserSorted($userId, "Name");

}


//////////
// Knowledge
//////////

// knowledge is matched on user/profile
// knowledge profile group is: 'knowledgefield'
// knowledge profile name is: knowledge['field'] (not necesary!!!)

function ScioMinoApiSaveKnowledge ($knowledge, $userId, $access) {

	//$knowledgeList = array();
	//$knowledgeList['knowledgefield'] = $knowledge;

	$profile = array();
	$profile['group'] = 'knowledgefield';
	//$profile['name'] = $knowledge['field'];

	return UserApiSaveUserProfileAnnotationList($profile, $knowledge, $userId, $access);

}

function ScioMinoApiUpdateKnowledge ($knowledge, $userId, $knowledgeId) {

	return UserApiUpdateUserProfileAnnotation($knowledge, $userId, $knowledgeId);

}

function ScioMinoApiGetKnowledge ($userId, $knowledgeId) {

	return UserApiGetUserProfile($userId, $knowledgeId);

}

function ScioMinoApiListKnowledge ($userId) {

	return UserApiListUserProfileByUser("knowledgefield", $userId);

}

function ScioMinoApiDeleteKnowledge ($userId, $knowledgeId) {

	return UserApiDeleteUserProfile($userId, $knowledgeId);

}



//////////
// Hobby
//////////

// hobby is matched on user/profile
// hobby profile group is: 'hobbyfield'

function ScioMinoApiSaveHobby ($hobby, $userId, $access) {

	$profile = array();
	$profile['group'] = 'hobbyfield';

	return UserApiSaveUserProfileAnnotationList($profile, $hobby, $userId, $access);

}

function ScioMinoApiUpdateHobby ($hobby, $userId, $hobbyId) {

	return UserApiUpdateUserProfileAnnotation($hobby, $userId, $hobbyId);

}

function ScioMinoApiGetHobby ($userId, $hobbyId) {

	return UserApiGetUserProfile($userId, $hobbyId);

}

function ScioMinoApiListHobby ($userId) {

	return UserApiListUserProfileByUser("hobbyfield", $userId);

}

function ScioMinoApiDeleteHobby ($userId, $hobbyId) {

	return UserApiDeleteUserProfile($userId, $hobbyId);

}



//////////
// Publication: Social Network
//////////

// social network is matched on publication
// social network section name is: SocialNetwork

function ScioMinoApiSaveSocialNetwork ($network, $userId, $access) {

	$networkId = UserApiCreatePublication($userId, '1', 'SocialNetwork');

	return UserApiSavePublicationAnnotationList($network, $networkId, $access);

}

function ScioMinoApiUpdateSocialNetwork ($network, $userId, $networkId) {

	return UserApiUpdatePublicationAnnotationListByPublication ($network, $networkId);

}

function ScioMinoApiGetSocialNetwork ($userId, $networkId) {

	return UserApiListPublicationById($userId, $networkId);

}

function ScioMinoApiListSocialNetwork ($userId) {

	return UserApiListPublicationByName($userId, "SocialNetwork");

}

function ScioMinoApiDeleteSocialNetwork ($userId, $networkId) {

	return UserApiDeletePublication($userId, $networkId);

}

//////////
// Publication: Blog
//////////

// Blog is matched on publication
// Blog section name is: Blog

function ScioMinoApiSaveBlog ($blog, $userId, $access) {

	$blogId = UserApiCreatePublication($userId, '2', 'Blog');

	return UserApiSavePublicationAnnotationList($blog, $blogId, $access);

}

function ScioMinoApiUpdateBlog ($blog, $userId, $blogId) {

	return UserApiUpdatePublicationAnnotationListByPublication ($blog, $blogId);

}

function ScioMinoApiGetBlog ($userId, $blogId) {

	return UserApiListPublicationById($userId, $blogId);

}

function ScioMinoApiListBlog ($userId) {

	return UserApiListPublicationByName($userId, "Blog");

}

function ScioMinoApiDeleteBlog ($userId, $blogId) {

	return UserApiDeletePublication($userId, $blogId);

}

//////////
// Publication: Share
//////////

// Share is matched on publication
// Share section name is: Share

function ScioMinoApiSaveShare ($share, $userId, $access) {

	$shareId = UserApiCreatePublication($userId, '3', 'Share');

	return UserApiSavePublicationAnnotationList($share, $shareId, $access);

}

function ScioMinoApiUpdateShare ($share, $userId, $shareId) {

	return UserApiUpdatePublicationAnnotationListByPublication ($share, $shareId);

}

function ScioMinoApiGetShare ($userId, $shareId) {

	return UserApiListPublicationById($userId, $shareId);

}

function ScioMinoApiListShare ($userId) {

	return UserApiListPublicationByName($userId, "Share");

}

function ScioMinoApiDeleteShare ($userId, $shareId) {

	return UserApiDeletePublication($userId, $shareId);

}

//////////
// Publication: Website
//////////

// Website is matched on publication
// Website section name is: Website

function ScioMinoApiSaveWebsite ($website, $userId, $access) {

	$websiteId = UserApiCreatePublication($userId, '4', 'Website');

	return UserApiSavePublicationAnnotationList($website, $websiteId, $access);

}

function ScioMinoApiUpdateWebsite ($website, $userId, $websiteId) {

	return UserApiUpdatePublicationAnnotationListByPublication ($website, $websiteId);

}

function ScioMinoApiGetWebsite ($userId, $websiteId) {

	return UserApiListPublicationById($userId, $websiteId);

}

function ScioMinoApiListWebsite ($userId) {

	return UserApiListPublicationByName($userId, "Website");

}

function ScioMinoApiDeleteWebsite ($userId, $websiteId) {

	return UserApiDeletePublication($userId, $websiteId);

}

//////////
// Publication: OtherPub
//////////

// OtherPub is matched on publication
// OtherPub section name is: Other

function ScioMinoApiSaveOtherPub ($otherPub, $userId, $access) {

	$otherPubId = UserApiCreatePublication($userId, '5', 'Other');

	return UserApiSavePublicationAnnotationList($otherPub, $otherPubId, $access);

}

function ScioMinoApiUpdateOtherPub ($otherPub, $userId, $otherPubId) {

	return UserApiUpdatePublicationAnnotationListByPublication ($otherPub, $otherPubId);

}

function ScioMinoApiGetOtherPub ($userId, $otherPubId) {

	return UserApiListPublicationById($userId, $otherPubId);

}

function ScioMinoApiListOtherPub ($userId) {

	return UserApiListPublicationByName($userId, "Other");

}

function ScioMinoApiDeleteOtherPub ($userId, $otherPubId) {

	return UserApiDeletePublication($userId, $otherPubId);

}

//////////
// Experience: Company
//////////

// Company is matched on experience
// Company section name is: Company

function ScioMinoApiSaveCompany ($company, $userId, $access) {

	$companyId = UserApiCreateExperience($userId, '1', 'Company');

	return UserApiSaveExperienceAnnotationList($company, $companyId, $access);

}

function ScioMinoApiUpdateCompany ($company, $userId, $companyId) {

	return UserApiUpdateExperienceAnnotationListByExperience ($company, $companyId);

}

function ScioMinoApiGetCompany ($userId, $companyId) {

	return UserApiListExperienceById($userId, $companyId);

}

function ScioMinoApiListCompany ($userId) {

	return UserApiListExperienceByName($userId, "Company");

}

function ScioMinoApiDeleteCompany ($userId, $companyId) {

	return UserApiDeleteExperience($userId, $companyId);

}

//////////
// Experience: Event
//////////

// Event is matched on experience
// Event section name is: Event

function ScioMinoApiSaveEvent ($event, $userId, $access) {

	$eventId = UserApiCreateExperience($userId, '2', 'Event');

	return UserApiSaveExperienceAnnotationList($event, $eventId, $access);

}

function ScioMinoApiUpdateEvent ($event, $userId, $eventId) {

	return UserApiUpdateExperienceAnnotationListByExperience ($event, $eventId);

}

function ScioMinoApiGetEvent ($userId, $eventId) {

	return UserApiListExperienceById($userId, $eventId);

}

function ScioMinoApiListEvent ($userId) {

	return UserApiListExperienceByName($userId, "Event");

}

function ScioMinoApiDeleteEvent ($userId, $eventId) {

	return UserApiDeleteExperience($userId, $eventId);

}

//////////
// Experience: Education
//////////

// Education is matched on experience
// Education section name is: Education

function ScioMinoApiSaveEducation ($education, $userId, $access) {

	$educationId = UserApiCreateExperience($userId, '3', 'Education');

	return UserApiSaveExperienceAnnotationList($education, $educationId, $access);

}

function ScioMinoApiUpdateEducation ($education, $userId, $educationId) {

	return UserApiUpdateExperienceAnnotationListByExperience ($education, $educationId);

}

function ScioMinoApiGetEducation ($userId, $educationId) {

	return UserApiListExperienceById($userId, $educationId);

}

function ScioMinoApiListEducation ($userId) {

	return UserApiListExperienceByName($userId, "Education");

}

function ScioMinoApiDeleteEducation ($userId, $educationId) {

	return UserApiDeleteExperience($userId, $educationId);

}

//////////
// Experience: Product
//////////

// Product is matched on experience
// Product section name is: Product

function ScioMinoApiSaveProduct ($product, $userId, $access) {

	$productId = UserApiCreateExperience($userId, '4', 'Product');

	return UserApiSaveExperienceAnnotationList($product, $productId, $access);

}

function ScioMinoApiUpdateProduct ($product, $userId, $productId) {

	return UserApiUpdateExperienceAnnotationListByExperience ($product, $productId);

}

function ScioMinoApiGetProduct ($userId, $productId) {

	return UserApiListExperienceById($userId, $productId);

}

function ScioMinoApiListProduct ($userId) {

	return UserApiListExperienceByName($userId, "Product");

}

function ScioMinoApiDeleteProduct ($userId, $productId) {

	return UserApiDeleteExperience($userId, $productId);

}



//////////
// #tags
//////////

// tag is matched on user/profile
// tag profile group is: 'tag'
// tag profile name is: tag['name'] (not necesary!!!)

function ScioMinoApiSaveTag ($tag, $userId, $access) {

	$profile = array();
	$profile['group'] = 'tag';
	//$profile['name'] = $tag['name'];

	return UserApiSaveUserProfileAnnotationList($profile, $tag, $userId, $access);

}

function ScioMinoApiUpdateTag ($tag, $userId, $tagId) {

	return UserApiUpdateUserProfileAnnotation($tag, $userId, $tagId);

}

function ScioMinoApiGetTag ($userId, $tagId) {

	return UserApiGetUserProfile($userId, $tagId);

}

function ScioMinoApiListTag ($userId) {

	return UserApiListUserProfileByUser("tag", $userId);

}

function ScioMinoApiDeleteTag ($userId, $tagId) {

	return UserApiDeleteUserProfile($userId, $tagId);

}


?>
