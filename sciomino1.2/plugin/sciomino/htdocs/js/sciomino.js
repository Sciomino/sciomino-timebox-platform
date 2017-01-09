
// transfer with XMLexchange
ScioMinoTransfer = new XMLexchange();

// define ScioMino
if (!window.ScioMino) {
  ScioMino = {};
}

//
// USER
// - 1.2
ScioMino.User = {

  // profile
  loadProfile: function() {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/web/user-profile", "", "ScioMino.User.loadProfile_callback", "TEXT");
  },

  loadProfile_callback: function(data) {
	document.getElementById('userWindow').innerHTML=data;
  },

  actionProfile: function() {
	
	checked = checkFormInput("user_form");

	statusForm = checked[0];
	vars = checked[1];

        // validate
        if (statusForm == 0 ) {
		ScioMino.User.actionProfile_alert(language('session_form_fill'));
	}
	else {
		sc.displayMessage({message : language('session_form_loading')});
		// SUBMIT roept 'submitFrame' aan, in submitFrame wordt de 'callback' geladen.
		//ScioMinoTransfer.request("SUBMIT", "/web/user-profile", document.getElementById("user_form"),"", "");
		// use setTimeout for a chrome workaround
		setTimeout('ScioMinoTransfer.request("SUBMIT", "' + XCOW_B['url'] + '/web/user-profile", document.getElementById("user_form"),"", "")',10);
        }

  },

  actionProfile_alert: function(message) {
	//document.getElementById('userWindowAlert').innerHTML=message;
	sc.displayMessage({message : message, displayTime : 2000});
  },

  actionProfile_callback: function(data) {
	// document.getElementById('userWindowAlert').innerHTML=data;
	// setTimeout("document.getElementById('userWindowAlert').innerHTML=''", 2000);
	sc.displayMessage({message : data, displayTime : 2000});

	// reload also, to fix bug where i change the action of the form in XMLexchange.js
	setTimeout("ScioMino.User.loadProfile()", 2000);
  },

  actionProfile_callback_reload: function(data) {
	//document.getElementById('userWindowAlert').innerHTML=data;
	sc.displayMessage({message : data, displayTime : 2000});

	// reload
	setTimeout("ScioMino.User.loadProfile()", 2000);
  },

  // focus
  loadFocus: function() {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/focus-list", "", "ScioMino.User.loadFocus_callback", "TEXT");
  },

  loadFocus_callback: function(data) {
	document.getElementById('userWindow').innerHTML=data;
  },

  // connect
  loadConnect: function() {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/connect-list", "", "ScioMino.User.loadConnect_callback", "TEXT");
  },

  loadConnect_callback: function(data) {
	document.getElementById('userWindow').innerHTML=data;
  },

  // networks
  loadNetworks: function() {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/network-list", "", "ScioMino.User.loadNetworks_callback", "TEXT");
  },

  loadNetworks_callback: function(data) {
	document.getElementById('userWindow').innerHTML=data;
  },

  // same
  same: function(user) {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/user-same?user="+user, "", "ScioMino.User.same_callback", "TEXT");
  },

  same_callback: function(data) {
	document.getElementById('userSameWindow').innerHTML=data;
  },

  // personal
  personal: function() {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/user-view-personal", "", "ScioMino.User.personal_callback", "TEXT");
  },

  personal_callback: function(data) {
	document.getElementById('userPersonalWindow').innerHTML=data;
	// prepare modal
    var fancyboxOptions = {
        autoDimensions : false,
        width : 630,
        height : 580,
        onComplete : function () {
            $('a.modal').fancybox(fancyboxOptions);
            if (document.getElementById('YMap-container')) {
                sc_YMap.createResultMap(sc_getData);
            }

        }
    };
    $('a.modal').fancybox(fancyboxOptions);
  },
  
  // faces
  faces: function() {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/user-view-faces", "", "ScioMino.User.faces_callback", "TEXT");
  },

  faces_callback: function(data) {
	document.getElementById('userFacesWindow').innerHTML=data;
  }
  
}

//
// Knowledge
// - 1.2

ScioMino.KnowledgeList = {

  load: function() {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/knowledge-list", "", "ScioMino.KnowledgeList.load_callback", "TEXT");
  },

  load_callback: function(data) {
	// put data in this innerHTML
	document.getElementById('knowledgeListWindow').innerHTML=data;
	// OR put data in new span and append to DOM
	//document.getElementById('knowledgeListWindow').innerHTML="";
        //var newSpan = document.createElement("span");
        //newSpan.innerHTML = data;
        //document.getElementById('knowledgeListWindow').appendChild(newSpan);
	
  }

}

//
// Hobby
// - 1.2

ScioMino.HobbyList = {

  load: function() {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/hobby-list", "", "ScioMino.HobbyList.load_callback", "TEXT");
  },

  load_callback: function(data) {
	document.getElementById('hobbyListWindow').innerHTML=data;
  }

}

//
// #Tags
// - 1.2

ScioMino.TagList = {

  load: function() {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/tag-list", "", "ScioMino.TagList.load_callback", "TEXT");
  },

  load_callback: function(data) {
	document.getElementById('tagListWindow').innerHTML=data;
  }

}

//
// Publication: SocialNetwork
// -1.2

ScioMino.SocialNetworkList = {

  load: function() {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/socialnetwork-list", "", "ScioMino.SocialNetworkList.load_callback", "TEXT");
  },

  load_callback: function(data) {
	document.getElementById('socialNetworkListWindow').innerHTML=data;
  }

}

//
// Publication: Blog
// - 1.2

ScioMino.BlogList = {

  load: function() {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/blog-list", "", "ScioMino.BlogList.load_callback", "TEXT");
  },

  load_callback: function(data) {
	document.getElementById('blogListWindow').innerHTML=data;
  }

}

//
// Publication: Share
// - 1.2

ScioMino.ShareList = {

  load: function() {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/share-list", "", "ScioMino.ShareList.load_callback", "TEXT");
  },

  load_callback: function(data) {
	document.getElementById('shareListWindow').innerHTML=data;
  }

}

//
// Publication: Website
// -1.2

ScioMino.WebsiteList = {

  load: function() {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/website-list", "", "ScioMino.WebsiteList.load_callback", "TEXT");
  },

  load_callback: function(data) {
	document.getElementById('websiteListWindow').innerHTML=data;
  }

}

//
// Publication: OtherPub
// - 1.2

ScioMino.OtherPubList = {

  load: function() {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/otherPub-list", "", "ScioMino.OtherPubList.load_callback", "TEXT");
  },

  load_callback: function(data) {
	document.getElementById('otherPubListWindow').innerHTML=data;
  }

}

//
// Experience: Company
// 1.2

ScioMino.CompanyNew = {

  load: function() {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/company-new-form", "", "ScioMino.CompanyNew.load_callback", "TEXT");
  },

  load_callback: function(data) {
	document.getElementById('companyFormWindow').innerHTML=data;
  },

  action: function() {
	
	checked = checkFormInput("company_new_form");

	statusForm = checked[0];
	vars = checked[1];

        // validate
        if (statusForm == 0 ) {
		ScioMino.CompanyNew.action_alert(language('session_form_fill'));
	}
	else {
                ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/company-new-form?"+vars, "","ScioMino.CompanyNew.action_callback", "TEXT");
        }

  },

  action_alert: function(message) {
	document.getElementById('companyFormWindowAlert').innerHTML=message;
  },

  action_callback: function(data) {
	document.getElementById('companyFormWindow').innerHTML=data;
	// clear div
	setTimeout("document.getElementById('companyFormWindow').innerHTML=''", 2000);
	ScioMino.CompanyList.load();
  }

}

ScioMino.CompanyEdit = {

  load: function(companyId) {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/company-edit-form?companyId="+companyId, "", "ScioMino.CompanyEdit.load_callback", "TEXT");
  },

  load_callback: function(data) {
	document.getElementById('companyFormWindow').innerHTML=data;
  },

  action: function() {
	
	checked = checkFormInput("company_edit_form");

	statusForm = checked[0];
	vars = checked[1];

        // validate
        if (statusForm == 0 ) {
		ScioMino.CompanyNew.action_alert(language('session_form_fill'));
	}
	else {
                ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/company-edit-form?"+vars, "","ScioMino.CompanyEdit.action_callback", "TEXT");
        }

  },

  action_alert: function(message) {
	document.getElementById('companyFormWindowAlert').innerHTML=message;
  },

  action_callback: function(data) {
	document.getElementById('companyFormWindow').innerHTML=data;
	// clear div
	setTimeout("document.getElementById('companyFormWindow').innerHTML=''", 2000);
	ScioMino.CompanyList.load();
  }

}

ScioMino.CompanyList = {

  load: function() {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/company-list", "", "ScioMino.CompanyList.load_callback", "TEXT");
  },

  load_callback: function(data) {
	document.getElementById('companyListWindow').innerHTML=data;
  }

}

ScioMino.CompanyDelete = {

  action: function(companyId) {
	
        // TODO: verify request: 'are you sure?'
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/company-delete?companyId="+companyId, "","ScioMino.CompanyDelete.action_callback", "TEXT");

  },

  action_callback: function(data) {
	// update list
	ScioMino.CompanyList.load();
  }

}

//
// Experience: Event
// -1.2

ScioMino.EventNew = {

  load: function() {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/event-new-form", "", "ScioMino.EventNew.load_callback", "TEXT");
  },

  load_callback: function(data) {
	document.getElementById('eventFormWindow').innerHTML=data;
  },

  action: function() {
	
	checked = checkFormInput("event_new_form");

	statusForm = checked[0];
	vars = checked[1];

        // validate
        if (statusForm == 0 ) {
		ScioMino.EventNew.action_alert(language('session_form_fill'));
	}
	else {
                ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/event-new-form?"+vars, "","ScioMino.EventNew.action_callback", "TEXT");
        }

  },

  action_alert: function(message) {
	document.getElementById('eventFormWindowAlert').innerHTML=message;
  },

  action_callback: function(data) {
	document.getElementById('eventFormWindow').innerHTML=data;
	// clear div
	setTimeout("document.getElementById('eventFormWindow').innerHTML=''", 2000);
	ScioMino.EventList.load();
  }

}

ScioMino.EventEdit = {

  load: function(eventId) {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/event-edit-form?eventId="+eventId, "", "ScioMino.EventEdit.load_callback", "TEXT");
  },

  load_callback: function(data) {
	document.getElementById('eventFormWindow').innerHTML=data;
  },

  action: function() {
	
	checked = checkFormInput("event_edit_form");

	statusForm = checked[0];
	vars = checked[1];

        // validate
        if (statusForm == 0 ) {
		ScioMino.EventNew.action_alert(language('session_form_fill'));
	}
	else {
                ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/event-edit-form?"+vars, "","ScioMino.EventEdit.action_callback", "TEXT");
        }

  },

  action_alert: function(message) {
	document.getElementById('eventFormWindowAlert').innerHTML=message;
  },

  action_callback: function(data) {
	document.getElementById('eventFormWindow').innerHTML=data;
	// clear div
	setTimeout("document.getElementById('eventFormWindow').innerHTML=''", 2000);
	ScioMino.EventList.load();
  }

}

ScioMino.EventList = {

  load: function() {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/event-list", "", "ScioMino.EventList.load_callback", "TEXT");
  },

  load_callback: function(data) {
	document.getElementById('eventListWindow').innerHTML=data;
  }

}

ScioMino.EventDelete = {

  action: function(eventId) {
	
        // TODO: verify request: 'are you sure?'
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/event-delete?eventId="+eventId, "","ScioMino.EventDelete.action_callback", "TEXT");

  },

  action_callback: function(data) {
	// update list
	ScioMino.EventList.load();
  }

}

//
// Experience: Education
// - 1.2

ScioMino.EducationNew = {

  load: function() {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/education-new-form", "", "ScioMino.EducationNew.load_callback", "TEXT");
  },

  load_callback: function(data) {
	document.getElementById('educationFormWindow').innerHTML=data;
  },

  action: function() {
	
	checked = checkFormInput("education_new_form");

	statusForm = checked[0];
	vars = checked[1];

        // validate
        if (statusForm == 0 ) {
		ScioMino.EducationNew.action_alert(language('session_form_fill'));
	}
	else {
                ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/education-new-form?"+vars, "","ScioMino.EducationNew.action_callback", "TEXT");
        }

  },

  action_alert: function(message) {
	document.getElementById('educationFormWindowAlert').innerHTML=message;
  },

  action_callback: function(data) {
	document.getElementById('educationFormWindow').innerHTML=data;
	// clear div
	setTimeout("document.getElementById('educationFormWindow').innerHTML=''", 2000);
	ScioMino.EducationList.load();
  }

}

ScioMino.EducationEdit = {

  load: function(educationId) {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/education-edit-form?educationId="+educationId, "", "ScioMino.EducationEdit.load_callback", "TEXT");
  },

  load_callback: function(data) {
	document.getElementById('educationFormWindow').innerHTML=data;
  },

  action: function() {
	
	checked = checkFormInput("education_edit_form");

	statusForm = checked[0];
	vars = checked[1];

        // validate
        if (statusForm == 0 ) {
		ScioMino.EducationNew.action_alert(language('session_form_fill'));
	}
	else {
                ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/education-edit-form?"+vars, "","ScioMino.EducationEdit.action_callback", "TEXT");
        }

  },

  action_alert: function(message) {
	document.getElementById('educationFormWindowAlert').innerHTML=message;
  },

  action_callback: function(data) {
	document.getElementById('educationFormWindow').innerHTML=data;
	// clear div
	setTimeout("document.getElementById('educationFormWindow').innerHTML=''", 2000);
	ScioMino.EducationList.load();
  }

}

ScioMino.EducationList = {

  load: function() {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/education-list", "", "ScioMino.EducationList.load_callback", "TEXT");
  },

  load_callback: function(data) {
	document.getElementById('educationListWindow').innerHTML=data;
  }

}

ScioMino.EducationDelete = {

  action: function(educationId) {
	
        // TODO: verify request: 'are you sure?'
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/education-delete?educationId="+educationId, "","ScioMino.EducationDelete.action_callback", "TEXT");

  },

  action_callback: function(data) {
	// update list
	ScioMino.EducationList.load();
  }

}

//
// Experience: Product
// -1.2

ScioMino.ProductNew = {

  load: function() {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/product-new-form", "", "ScioMino.ProductNew.load_callback", "TEXT");
  },

  load_callback: function(data) {
	document.getElementById('productFormWindow').innerHTML=data;
  },

  action: function() {
	
	checked = checkFormInput("product_new_form");

	statusForm = checked[0];
	vars = checked[1];

        // validate
        if (statusForm == 0 ) {
		ScioMino.ProductNew.action_alert(language('session_form_fill'));
	}
	else {
		ScioMinoTransfer.request("SUBMIT", XCOW_B['url'] + "/snippet/product-new-form?"+vars, document.getElementById("product_new_form"),"", "");
                // ScioMinoTransfer.request("GET", "/snippet/product-new-form?"+vars, "","ScioMino.ProductNew.action_callback", "TEXT");
        }

  },

  action_alert: function(message) {
	document.getElementById('productFormWindowAlert').innerHTML=message;
  },

  action_callback: function(data) {
	document.getElementById('productFormWindow').innerHTML=data;
	// clear div
	setTimeout("document.getElementById('productFormWindow').innerHTML=''", 2000);
	ScioMino.ProductList.load();
  }

}

ScioMino.ProductEdit = {

  load: function(productId) {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/product-edit-form?productId="+productId, "", "ScioMino.ProductEdit.load_callback", "TEXT");
  },

  load_callback: function(data) {
	document.getElementById('productFormWindow').innerHTML=data;
  },

  action: function() {
	
	checked = checkFormInput("product_edit_form");

	statusForm = checked[0];
	vars = checked[1];

        // validate
        if (statusForm == 0 ) {
		ScioMino.ProductNew.action_alert(language('session_form_fill'));
	}
	else {
		ScioMinoTransfer.request("SUBMIT", XCOW_B['url'] + "/snippet/product-edit-form?"+vars, document.getElementById("product_edit_form"),"", "");
                // ScioMinoTransfer.request("GET", "/snippet/product-edit-form?"+vars, "","ScioMino.ProductEdit.action_callback", "TEXT");
        }

  },

  action_alert: function(message) {
	document.getElementById('productFormWindowAlert').innerHTML=message;
  },

  action_callback: function(data) {
	document.getElementById('productFormWindow').innerHTML=data;
	// clear div
	setTimeout("document.getElementById('productFormWindow').innerHTML=''", 2000);
	ScioMino.ProductList.load();
  }

}

ScioMino.ProductList = {

  load: function() {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/product-list", "", "ScioMino.ProductList.load_callback", "TEXT");
  },

  load_callback: function(data) {
	document.getElementById('productListWindow').innerHTML=data;
  }

}

ScioMino.ProductDelete = {

  action: function(productId) {
	
        // TODO: verify request: 'are you sure?'
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/product-delete?productId="+productId, "","ScioMino.ProductDelete.action_callback", "TEXT");

  },

  action_callback: function(data) {
	// update list
	ScioMino.ProductList.load();
  }

}

//
// SEARCH Detail
// - 1.2

ScioMino.SearchDetail = {

  // focus is deel van de query string, deze wordt dus niet gecodeerd met secureTransfer(focus)... verwarrend!?...
  load: function(detail, focus) {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/search-detail?"+focus+"&detail="+detail, "", "ScioMino.SearchDetail.load_callback", "TEXT");
  },

  load_callback: function(data) {
	document.getElementById('searchDetailWindow').innerHTML=data;
  }

}

//
// list knowledge field
// - 1.2

ScioMino.ListKnowledgeFields = {

  load: function(limit, format) {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/list-knowledge-fields?limit="+limit+"&format="+format, "", "ScioMino.ListKnowledgeFields.load_callback", "TEXT");
  },

  loadAlphabet: function(start, limit) {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/list-knowledge-fields?start="+encodeURIComponent(start)+"&limit="+limit, "", "ScioMino.ListKnowledgeFields.load_callback", "TEXT");
  },

  loadQuery: function(limit) {
	query = document.getElementById("searchKnowledgeBox").value;
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/list-knowledge-fields?limit="+limit+"&start="+encodeURIComponent(query), "", "ScioMino.ListKnowledgeFields.load_callback", "TEXT");
  },

  load_callback: function(data) {
	document.getElementById('knowledgeListWindow').innerHTML=data;
  }

}

//
// list hobby field
// - 1.2

ScioMino.ListHobbyFields = {

  load: function(limit) {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/list-hobby-fields?type=hobby&limit="+limit, "", "ScioMino.ListHobbyFields.load_callback", "TEXT");
  },

  loadAlphabet: function(start, limit) {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/list-hobby-fields?type=hobby&start="+encodeURIComponent(start)+"&limit="+limit, "", "ScioMino.ListHobbyFields.load_callback", "TEXT");
  },

  loadQuery: function(limit) {
	query = document.getElementById("searchHobbyBox").value;
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/list-hobby-fields?type=hobby&limit="+limit+"&start="+encodeURIComponent(query), "", "ScioMino.ListHobbyFields.load_callback", "TEXT");
  },

  load_callback: function(data) {
	document.getElementById('hobbyListWindow').innerHTML=data;
  }

}

//
// list tag names
// - 1.2

ScioMino.ListTagNames = {

  load: function(limit) {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/list-tag-names?type=tag&limit="+limit, "", "ScioMino.ListTagNames.load_callback", "TEXT");
  },

  loadAlphabet: function(start, limit) {
	start = ScioMino.ListTagNames.tagCompletion(start);
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/list-tag-names?type=tag&start="+encodeURIComponent(start)+"&limit="+limit, "", "ScioMino.ListTagNames.load_callback", "TEXT");
  },

  loadQuery: function(limit) {
	query = document.getElementById("searchTagBox").value;
	query = ScioMino.ListTagNames.tagCompletion(query);
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/list-tag-names?type=tag&limit="+limit+"&start="+encodeURIComponent(query), "", "ScioMino.ListTagNames.load_callback", "TEXT");
  },

  load_callback: function(data) {
	document.getElementById('tagListWindow').innerHTML=data;
  },

	tagCompletion: function(tag) {
		if (tag.indexOf("#") == 0) {
			// remove multiple #
			tag = tag.replace(/^#+/,"");
			tag = "#" + tag;
		}
		else {
			tag = "#" + tag;
		}
		
		return tag;
	}
}

//
// list experience field
// - 1.2

ScioMino.ListExperienceFields = {

  load: function(limit, format) {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/list-experience-fields?limit="+limit+"&format="+format, "", "ScioMino.ListExperienceFields.load_callback", "TEXT");
  },

  load_callback: function(data) {
	document.getElementById('experienceListWindow').innerHTML=data;
  }

}

//
// Focus
// - 1.2

ScioMino.Focus = {

  // reload list
  reload: function() {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/focus-list", "", "ScioMino.Focus.reload_callback", "TEXT");
  },

  reload_callback: function(data) {
	document.getElementById('userWindow').innerHTML=data;

  }

}

ScioMino.FocusNew = {

  // focus is deel van de query string, deze wordt dus niet gecodeerd met secureTransfer(focus)... verwarrend!?...
  action: function(focus) {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/focus-new?"+focus, "", "ScioMino.FocusNew.action_callback", "TEXT");
  },

  action_callback: function(data) {
	//document.getElementById('focusWindow').innerHTML=data;
	//setTimeout("document.getElementById('focusWindow').innerHTML=''", 2000);
	sc.displayMessage({message : data, displayTime : 2000});
  }

}

ScioMino.FocusDelete = {

  action: function(focusId) {
	
        // TODO: verify request: 'are you sure?'
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/focus-delete?focusId="+focusId, "","ScioMino.FocusDelete.action_callback", "TEXT");

  },

  action_callback: function(data) {
	// update list
	ScioMino.Focus.reload();
  }

}
//
// Lijsten
// - 1.2

ScioMino.List = {
   //
  // check list item
  //
  check: function(user, group, e) {
	element = getEventElement(e);

	if (element.checked) {
		ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/list-newUser?group="+group+"&user="+user, "", "ScioMino.List.check_callback", "TEXT");
	}
	else {
		ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/list-deleteUser?group="+group+"&user="+user, "", "ScioMino.List.check_callback", "TEXT");
	}
  },

  check_callback: function(data) {
  }

}


//
// Activity
// - 1.2

ScioMino.ActivityNew = {

  action: function(title, description) {
	
        ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/activity-new?title="+title+"&description="+secureTransfer(description), "","ScioMino.ActivityNew.action_callback", "TEXT");

  },

  action_callback: function(data) {
	//document.getElementById('activityNewWindow').innerHTML=data;
	//// clear div
	//setTimeout("document.getElementById('activityNewWindow').innerHTML=''", 2000);
	sc.displayMessage({message : data, displayTime : 2000});
	setTimeout("window.location.replace('/')", 2000);
  },

  actionMotd: function() {
	title = "motd";
	description = document.getElementById('motd').value;
        ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/activity-new?title="+title+"&description="+secureTransfer(description), "","ScioMino.ActivityNew.actionMotd_callback", "TEXT");

  },

  actionMotd_callback: function(data) {
	document.getElementById('activityNewWindow').innerHTML=data;
	// clear div
	setTimeout("document.getElementById('activityNewWindow').innerHTML=''", 2000);
  }

}

ScioMino.ActivityList = {

  load: function(limit) {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/activity-list?limit="+limit, "", "ScioMino.ActivityList.load_callback", "TEXT");
  },

  loadAll: function(limit) {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/activity-list?mode=all&limit="+limit, "", "ScioMino.ActivityList.loadAll_callback", "TEXT");
  },

  load_callback: function(data) {
	// put data in this innerHTML
	document.getElementById('activityListWindow').innerHTML=data;
	// OR put data in new span and append to DOM
	//document.getElementById('activityListWindow').innerHTML="";
        //var newSpan = document.createElement("span");
        //newSpan.innerHTML = data;
        //document.getElementById('activityListWindow').appendChild(newSpan);
  },

  loadAll_callback: function(data) {
	document.getElementById('activityListAllWindow').innerHTML=data;
  }

}

ScioMino.ActivityDelete = {

  action: function(activityId) {
	
        // TODO: verify request: 'are you sure?'
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/activity-delete?activityId="+activityId, "","ScioMino.ActivityDelete.action_callback", "TEXT");

  },

  action_callback: function(data) {
	// update list
	ScioMino.ActivityList.load(5);
  }

}

//
// Connect
// - 1.2

ScioMino.Connect = {

  //
  // load connections
  //
  loadLinkedin: function(user) {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/publication-linkedin-list?user="+user, "", "ScioMino.Connect.loadLinkedin_callback", "TEXT");
  },

  loadLinkedin_callback: function(data) {
	document.getElementById('publicationLinkedinListWindow').innerHTML=data;
	if(IN.parse) { IN.parse(); }
  },

  loadLinkedinSkills: function() {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/publication-linkedinSkills-list", "", "ScioMino.Connect.loadLinkedinSkills_callback", "TEXT");
  },

  loadLinkedinSkills_callback: function(data) {
	document.getElementById('publicationLinkedinSkillsWindow').innerHTML=data;
  },

  loadTwitterUser: function(user) {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/publication-twitter-user?user="+user, "", "ScioMino.Connect.loadTwitter_callback", "TEXT");
  },

  loadTwitterSearch: function(user, query) {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/publication-twitter-search?user="+user+"&query="+encodeURIComponent(query), "", "ScioMino.Connect.loadTwitter_callback", "TEXT");
  },

  loadTwitter_callback: function(data) {
	document.getElementById('publicationTwitterListWindow').innerHTML=data;
  },

  loadLink: function(user) {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/publication-link-list?user="+user, "", "ScioMino.Connect.loadLink_callback", "TEXT");
  },

  loadLink_callback: function(data) {
	document.getElementById('publicationLinkListWindow').innerHTML=data;
  },

  loadWikipedia: function(knowledge) {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/external-wikipedia?k="+knowledge, "", "ScioMino.Connect.loadWikipedia_callback", "TEXT");
  },

  loadWikipedia_callback: function(data) {
	document.getElementById('externalWikipediaWindow').innerHTML=data;
  },

  loadEvent: function(tag) {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/external-event?t="+tag, "", "ScioMino.Connect.loadEvent_callback", "TEXT");
  },

  loadEvent_callback: function(data) {
	document.getElementById('externalEventWindow').innerHTML=data;
  }

}

//
// ACT
// - 1.2

ScioMino.Act = {

  closeNew: function() {
	
	checked = checkFormInput("act_close_new_form");

	statusForm = checked[0];
	vars = checked[1];

        // validate
        if (statusForm == 0 ) {
		ScioMino.Act.closeNew_alert(language('session_form_fill'));
	}
	else {
		sc.displayMessage({message : language('session_form_loading')});
		// SUBMIT roept 'submitFrame' aan, in submitFrame wordt de 'callback' geladen.
		// use setTimeout for a chrome workaround
		setTimeout('ScioMinoTransfer.request("SUBMIT", "' + XCOW_B['url'] + '/snippet/act-close-new-form", document.getElementById("act_close_new_form"),"", "")',10);
        }

  },

  closeNew_alert: function(message) {
	sc.displayMessage({message : message, displayTime : 2000});
  },

  closeNew_callback: function(data, url) {
	sc.displayMessage({message : data, displayTime : 2000});

	// reload also, to fix bug where i change the action of the form in XMLexchange.js
	setTimeout("window.location.replace('" + url + "')", 2000);
  },

  closeEdit: function() {
	
	checked = checkFormInput("act_close_edit_form");

	statusForm = checked[0];
	vars = checked[1];

        // validate
        if (statusForm == 0 ) {
		ScioMino.Act.closeEdit_alert(language('session_form_fill'));
	}
	else {
		sc.displayMessage({message : language('session_form_loading')});
		// SUBMIT roept 'submitFrame' aan, in submitFrame wordt de 'callback' geladen.
		// use setTimeout for a chrome workaround
		setTimeout('ScioMinoTransfer.request("SUBMIT", "' + XCOW_B['url'] + '/snippet/act-close-edit-form", document.getElementById("act_close_edit_form"),"", "")',10);
        }

  },

  closeEdit_alert: function(message) {
	sc.displayMessage({message : message, displayTime : 2000});
  },

  closeEdit_callback: function(data, url) {
	sc.displayMessage({message : data, displayTime : 2000});

	// reload also, to fix bug where i change the action of the form in XMLexchange.js
	setTimeout("window.location.replace('" + url + "')", 2000);
  }

}

ScioMino.ActList = {

  load: function() {
	query = "mode=simpleZero&s[open]&limit=5";
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/act-list-simple?"+query, "", "ScioMino.ActList.load_callback", "TEXT");
  },

  load_callback: function(data) {
	// put data in this innerHTML
	document.getElementById('actListWindow').innerHTML=data;
  },

  loadPersonal: function() {
	query = "mode=simpleZero&limit=5";
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/act-list-personal?"+query, "", "ScioMino.ActList.loadPersonal_callback", "TEXT");
  },

  loadPersonal_callback: function(data) {
	// put data in this innerHTML
	document.getElementById('actListPersonalWindow').innerHTML=data;
  },

  loadKnowledgeOpen: function(knowledge, label) {
	query = "mode=simple&s[open]&k[" + knowledge + "]" + "&limit=3&label=" + label;
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/act-list-simple?"+query, "", "ScioMino.ActList.loadOpen_callback", "TEXT");
  },

  loadHobbyOpen: function(hobby, label) {
	query = "mode=simple&s[open]&h[" + hobby + "]" + "&limit=3&label=" + label;
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/act-list-simple?"+query, "", "ScioMino.ActList.loadOpen_callback", "TEXT");
  },

  loadTagOpen: function(tag, label) {
	query = "mode=simple&s[open]&q=" + tag + "" + "&limit=3&label=" + label;
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/act-list-simple?"+query, "", "ScioMino.ActList.loadOpen_callback", "TEXT");
  },

  loadOpen_callback: function(data) {
	// put data in this innerHTML
	document.getElementById('actListOpenWindow').innerHTML=data;
  },

  loadKnowledgeClosed: function(knowledge, label) {
	query = "mode=simple&s[closed]&k[" + knowledge + "]" + "&limit=3&label=" + label;
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/act-list-simple?"+query, "", "ScioMino.ActList.loadClosed_callback", "TEXT");
  },

  loadHobbyClosed: function(hobby, label) {
	query = "mode=simple&s[closed]&h[" + hobby + "]" + "&limit=3&label=" + label;
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/act-list-simple?"+query, "", "ScioMino.ActList.loadClosed_callback", "TEXT");
  },

  loadTagClosed: function(tag, label) {
	query = "mode=simple&s[closed]&q=" + tag + "" + "&limit=3&label=" + label;
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/act-list-simple?"+query, "", "ScioMino.ActList.loadClosed_callback", "TEXT");
  },

  loadClosed_callback: function(data) {
	// put data in this innerHTML
	document.getElementById('actListClosedWindow').innerHTML=data;
  }

}

//
// Social insights
// - 1.2

ScioMino.InsightsBirthday = {

  load: function(day) {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/insights-birthday?day="+day, "", "ScioMino.InsightsBirthday.load_callback", "TEXT");
  },

  load_callback: function(data) {
	// put data in this innerHTML
	document.getElementById('insightsBirthdayWindow').innerHTML=data;
  }

}

ScioMino.InsightsList = {

  load: function(list, limit) {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/insights-list?list="+list+"&limit="+limit, "", "ScioMino.InsightsList.load_callback", "TEXT");
  },

  loadAlphabet: function(list, start, limit) {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/insights-list?list="+list+"&limit="+limit+"&start="+start, "", "ScioMino.InsightsList.load_callback", "TEXT");
  },

  loadQuery: function(list, limit) {
	query = document.getElementById("insightListBox").value;
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/insights-list?list="+list+"&limit="+limit+"&start="+query, "", "ScioMino.InsightsList.load_callback", "TEXT");
  },

  load_callback: function(data) {
	document.getElementById('insightsListWindow').innerHTML=data;
	//document.getElementById('insightsListWindow').innerHTML="";
        //var newSpan = document.createElement("span");
        //newSpan.innerHTML = data;
        //document.getElementById('insightsListWindow').appendChild(newSpan);
	if(IN.parse) { IN.parse(); }
  }

}

ScioMino.Wizard = {

  load: function(step) {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/wizard/step"+step, "", "ScioMino.Wizard.load_callback", "TEXT");
  },

  load_callback: function(data) {
	document.getElementById('wizardWindow').innerHTML=data;
  },

  action: function(step) {
	
	checked = checkFormInput("wizard_form");

	statusForm = checked[0];
	vars = checked[1];

	// validate
	if (statusForm == 0 ) {
		ScioMino.Wizard.action_alert(language('session_form_fill'));
	}
	else {
        ScioMinoTransfer.request("GET", XCOW_B['url'] + "/wizard/step"+step+"?"+vars, "","ScioMino.Wizard.action_callback", "TEXT");
    }

  },

  action_alert: function(message) {
	document.getElementById('wizardAlertWindow').innerHTML=message;
  },

  action_callback: function(data) {
	document.getElementById('wizardWindow').innerHTML=data;
  },

  actionPhoto: function(step) {
	
	checked = checkFormInput("photo_form");

	statusForm = checked[0];
	vars = checked[1];

    // validate
    if (statusForm == 0 ) {
		ScioMino.Wizard.actionPhoto_alert(language('session_form_fill'));
	}
	else {
		sc.displayMessage({message : language('session_form_loading')});
		// SUBMIT roept 'submitFrame' aan, in submitFrame wordt de 'callback' geladen.
		// use setTimeout for a chrome workaround
		setTimeout('ScioMinoTransfer.request("SUBMIT", "' + XCOW_B['url'] + '/wizard/step' + step + '", document.getElementById("photo_form"), "", "")',10);
    }

  },

  actionPhoto_alert: function(message) {
	//document.getElementById('userWindowAlert').innerHTML=message;
	sc.displayMessage({message : message, displayTime : 2000});
  },

  actionPhoto_callback: function(data) {
	// document.getElementById('userWindowAlert').innerHTML=data;
	// setTimeout("document.getElementById('userWindowAlert').innerHTML=''", 2000);
	sc.displayMessage({message : data, displayTime : 2000});

	// reload also, to fix bug where i change the action of the form in XMLexchange.js
	setTimeout("ScioMino.Wizard.load(4)", 2000);
  },

  actionPhoto_callback_reload: function(data) {
	//document.getElementById('userWindowAlert').innerHTML=data;
	sc.displayMessage({message : data, displayTime : 2000});

	// reload
	setTimeout("ScioMino.Wizard.load(4)", 2000);
  }
}

ScioMino.Setting = {
  // account
  loadAccount: function() {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/account-delete", "", "ScioMino.Setting.loadAccount_callback", "TEXT");
  },

  loadAccount_callback: function(data) {
	document.getElementById('settingWindow').innerHTML=data;
  },
  
  // notification
  loadNotification: function() {
	ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/notification-list", "", "ScioMino.Setting.loadNotification_callback", "TEXT");
  },

  loadNotification_callback: function(data) {
	document.getElementById('settingWindow').innerHTML=data;
  },

  // check setting item
  check: function(user, id, name, e) {
	element = getEventElement(e);

	if (element.checked) {
		ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/setting-update?id="+id+"&name="+name+"&value=1"+"&user="+user, "", "ScioMino.Setting.check_callback", "TEXT");
	}
	else {
		ScioMinoTransfer.request("GET", XCOW_B['url'] + "/snippet/setting-update?id="+id+"&name="+name+"&value=0"+"&user="+user, "", "ScioMino.Setting.check_callback", "TEXT");
	}
  },

  check_callback: function(data) {
  	// update notification list (because the id changes after an update...)
	ScioMino.Setting.loadNotification();
  }

}
