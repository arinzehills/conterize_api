<!doctype html>
<html lang="en">
  <head>
    <title>Send Email and Password To Freelancer | Conterize</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>

    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-sm-12 m-auto">
                <h3> Freelancers Email and Password </h3>
                <p> Hey, </p>
                <p> Welcome to Conterize Content Creators Zone  </p>
                <p> You have been successfully added as a content creator at conterize <br> </p>
                <p>You can login using these credentials </p>
                <br/>
                <a class='button' href={{env('MAIL_DOMAIN_ADDRESS')."/login"
                                        .'?token='.$invite->accept_token
                                        .'&firstname='.$invite->firstname
                                        .'&lastname='.$invite->lastname
                                        .'&team_name='.$invite->team->name
                                        .'&email='.$invite->email
                                        }}
                    style="color:white;background: rgb(146, 3, 255);
                        border-radius: 10px;padding:1rem;
                        text-decoration: none;
                        border: none;">Login
                </a>
                <br />
                <b>Email:<b> {{$email_to}}
                <br/>
                <b>Your Password:<b> {{$plainPassword}}
                <p> Best Regards</p>
                <p> Team, Conterize </p>
            </div>
        </div>
    </div>
  </body>
</html>