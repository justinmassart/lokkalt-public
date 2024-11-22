<!DOCTYPE html>
<html lang="{!! explode('-', app()->currentLocale())[0] !!}">

<head>
    <title>{!! "$firstname $lastname - $about" !!}</title>
</head>

<body>
    <div>
        <h1>{!! "$firstname $lastname - $about" !!}</h1>
        <h2>Informations de l’utilisateur :</h2>
        <p>Prénom : {!! $firstname !!}</p>
        <p>Nom : {!! $lastname !!}</p>
        <p>Email : {!! $email !!}</p>
        <p>À propos : {!! $about !!}</p>
        <p>Langue-Pays : {!! $userLocale !!}</p>
    </div>
    <div>
        <h2>Message :</h2>
        <p>{!! $userMessage !!}</p>
    </div>
</body>

</html>
