var SC_WL=SC_WL||{};SC_WL.url="http://sciomino1.2/widgets/view";SC_WL.deparam=function(e){e=e.substring(e.indexOf("?")+1).split("&");var t={},n,r=decodeURIComponent;for(var i=e.length-1;i>=0;i--){n=e[i].split("=");t[r(n[0])]=r(n[1])}return t};SC_WL.setCss=function(e){var t=document.createElement("style");t.type="text/css";if(t.styleSheet){t.styleSheet.cssText=e}else{t.innerHTML=e}document.getElementsByTagName("head")[0].appendChild(t)};SC_WL.setContent=function(e){SC_WL.setCss(e.css);var t=document.createElement("div");t.setAttribute("id","sciomino_widget_container");t.innerHTML=e.html;SC_WL.target.parentNode.insertBefore(t,SC_WL.target)};SC_WL.target=document.getElementById("sciomino_widget");SC_WL.params=SC_WL.deparam(SC_WL.target.src);SC_WL.content=document.createElement("script");SC_WL.content.setAttribute("type","text/javascript");SC_WL.content.setAttribute("src",SC_WL.url+encodeURIComponent(SC_WL.target.src.substring(SC_WL.target.src.indexOf("?"))));SC_WL.target.appendChild(SC_WL.content)