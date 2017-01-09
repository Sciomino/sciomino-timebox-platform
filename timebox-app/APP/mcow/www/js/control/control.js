MCOW.Control = {

	"home" : 
		{ "model" : "base/home",
		  "view" : "base/home",
		  "next" : "page",
		  "prev" : "anotherpage",
		  "transition" : "none",
		  "transition-next" : "none",
		  "transition-prev" : "none",
		  "database" : "none",
		  "access" : 0  
		},
	
	"page" :
		{ "model" : "base/page",
		  "view" : "base/page",
		  "next" : "anotherpage",
		  "prev" : "home",
		  "transition" : "right",
		  "transition-next" : "right",
		  "transition-prev" : "left",
		  "database" : "none",
		  "access" : 0  
		},

	"anotherpage" :
		{ "model" : "base/anotherpage",
		  "view" : "base/anotherpage",
		  "next" : "home",
		  "prev" : "page",
		  "transition" : "right",
		  "transition-next" : "right",
		  "transition-prev" : "left",
		  "database" : "none",
		  "access" : 0  
		},

	"menu" : 
		{ "model" : "base/menu",
		  "view" : "base/menu",
		  "next" : "none",
		  "prev" : "none",
		  "transition" : "right",
		  "transition-next" : "none",
		  "transition-prev" : "none",
		  "database" : "none",
		  "access" : 0  
		},

	"error404" :
		{ "model" : "base/error404",
		  "view" : "base/error404",
		  "next" : "home",
		  "prev" : "home",
		  "transition" : "none",
		  "transition-next" : "none",
		  "transition-prev" : "none",
		  "database" : "none",
		  "access" : 0  
		},
	
}
