
{{-- Click here to join: <a href="{{route('accept_invite').'token='. $invite->accept_token}}">{{route('accept_invite').'?token='. $invite->accept_token}}</a> --}}
<!doctype html>
<html lang="en">
  <head>
    <title>Email Invitation to Join Team | Conterize</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>

    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-sm-12 m-auto">
                <div style="display: flex;justify-content: center">
                    <img height='70px'  src="{{asset("logo_crop.png")}}" >
                </div>
                <h3> Email Invitation to Join {{$team->name}}
                    at Conterize</h3>
                <p> Hey, </p>
                <p> You have been invited to join {{$team->name}} team on Conterize  </p>
                <p> Conterize is a content creation request platform, that 
                    enables content creators to request contents, such as articles, graphics and videos
                </p>
                <p> Enjoy! </p>
                <a class='button' href={{env('MAIL_DOMAIN_ADDRESS')."/acceptInvite"
                                        .'?token='.$invite->accept_token
                                        .'&firstname='.$invite->firstname
                                        .'&lastname='.$invite->lastname
                                        .'&team_name='.$invite->team->name
                                        .'&email='.$invite->email
                                        }}
                    style="color:white;background: rgb(146, 3, 255);
                        border-radius: 10px;padding:1rem;
                        text-decoration: none;
                        border: none;">Join {{$team->name}} on Conterize
                </a>
                
                <br/>
                <br/>
                <p> Best Regards</p>
                <p> Team, Conterize </p>
            </div>
        </div>
    </div>
  </body>
</html>