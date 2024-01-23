<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recover Password</title>
</head>
<body>
    <div style="width:100%">
        <div style="width: 600px;display:block;margin: 0 auto; margin-top:40px;">
            <div style="width:100%;height:50px;background-color: #339933;margin-bottom:50px;"></div>
            <img src="https://nutricionista-api.herokuapp.com/images/mail/logo.png" alt="Nutricionista" style="width:200px; display:block; margin:0px auto; margin-bottom:50px;">
            <p style="font-size: 22px;">Se ha restablecido tu contraseña</p>
            <p style="margin-bottom: 50px;">Los detalles de ingreso en la aplicación son los siguientes</p>
            <p style="margin-bottom: 2px;">Correo electrónico: {{ $user->email }}</p>
            <p style="margin-bottom: 5px;">Contraseña: {{ $password }}</p>
            <div style="width:100%;background-color: #339933;display: inline-flex;">
                <div style="width: 49%;padding-left:5px;">
                    <p style="margin-bottom: 2px;color:white;">© Nutricionista Natalia Segura</p>
                    <p style="margin-bottom: 2px;margin-top:2px;color:white;">San José, Costa Rica</p>
                    <p style="margin-top: 2px;color: white;">(506) 2253-3773</p>
                </div>
                <div style="width: 50%;display: inline-flex;">
                    <ul style="padding-left:0px;list-style:none;display:inline-flex;">
                        <li style="margin-right: 10px;"><img src="https://nutricionista-api.herokuapp.com/images/mail/playstore.png" alt="PlayStore" style="width: 130px;"></li>
                        <li><img src="https://nutricionista-api.herokuapp.com/images/mail/appstore.png" alt="AppStore" style="width: 130px;"></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
