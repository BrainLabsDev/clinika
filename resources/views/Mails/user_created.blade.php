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
            <img src="https://phplaravel-1203103-4252935.cloudwaysapps.com/images/mail/logo.png" alt="Clinika" style="width:200px; display:block; margin:0px auto; margin-bottom:50px;">
            <h3 style="font-size: 22px;">Detalles de Ingreso</h3>
            <p>Bienvenido (a) {{ $user->name }} </p>
            <p>Ahora que hemos registrado su información, puede aprovechar al máximo nuestra aplicación móvil. Descubra su información nutricional, planifique comidas saludables y consulte la lista de intercambios de alimentos de Natalia Segura.</p>
            <p>Para ingresar, use los siguientes detalles de inicio de sesión:</p>
            <p>Correo electrónico: {{ $user->email }}</p>
            <p>Contraseña: {{ $password }}</p>
            <div style="width:100%;background-color: #339933;display: inline-flex;">
                <div style="width: 49%;padding-left:5px;">
                    <p style="margin-bottom: 2px;">© Clinika Natalia Segura</p>
                    <p style="margin-bottom: 2px;margin-top:2px;">San José, Costa Rica</p>
                    <p style="margin-top: 2px;">(506) 2253-3773</p>
                </div>
                <div style="width: 50%;display: inline-flex;">
                    <ul style="padding-left:0px;list-style:none;display:inline-flex;">
                        <li style="margin-right: 10px;"><img src="https://phplaravel-1003446-3537062.cloudwaysapps.com/images/mail/playstore.png" alt="PlayStore" style="width: 130px;"></li>
                        <li><img src="https://phplaravel-1003446-3537062.cloudwaysapps.com/images/mail/appstore.png" alt="AppStore" style="width: 130px;"></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
