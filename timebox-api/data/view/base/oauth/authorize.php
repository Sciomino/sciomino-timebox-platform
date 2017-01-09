<html>
<head>
<title>Oauth - authorize</title>
</head>
<body>

<div>
	De volgende website wil graag toegang tot jouw persoonlijke informatie.
</div>
<br/><br/>

<div>
	Naam:
</div>
<div>
	<? echo $session['response']['param']['clientName']; ?>
</div>

<div>
	Beschrijving:
</div>
<div>
	<? echo $session['response']['param']['clientDescription']; ?>
</div>
<br/><br/>

       <div>
       		<form id="login_form" method="post" action="/oauth/authorize">
		<input type="hidden" name="token" value="<? echo $session['response']['param']['token']; ?>">
		<div>
			<div>
				Wil je toegang geven?
			</div>
			<div>
				<input type="radio" name="access" value="yes" CHECKED/> Ja
                       		<input type="radio" name="access" value="no"/> Nee
			</div>
                </div>

		<div>
                        <div>
                                Gebruikersnaam:
                        </div>
                        <div>
                                <input type="text" name="user" size="32" maxsize="127" />
                        </div>
		</div>

		<div>
                        <div>
                                Wachtwoord:
                        </div>
                        <div>
                                <input type="password" name="pass" size="32" maxsize="127" />
                        </div>
		</div>
		<div>
			<input type="submit" value=" Ok ">
		</div>
        	</form>
        </div>

<div>
	<? echo $session['response']['param']['error']; ?>
</div>

</body>
</html>
