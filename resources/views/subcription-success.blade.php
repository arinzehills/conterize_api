<!doctype html>
<html lang="en">
  <head>
    <title>Thank you for Subcribing | Conterize</title>
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
                <h3> Successfull Subscription Made </h3>
                <p> Hey, </p>
                <p> You have successfully subscribe to {{$plan_name}} plan for ${{$price}}</p>
                <p> You can now start placing content request <br> </p>
               
                <a class='button' href={{env('MAIL_DOMAIN_ADDRESS')."/dashboard/newrequest"
                                        }}
                    style="color:white;background: rgb(146, 3, 255);
                        border-radius: 10px;padding:1rem;
                        text-decoration: none;
                        border: none;">Place Request
                </a>
                <br />
                
                <p> Best Regards</p>
                <p> Team, Conterize </p>
            </div>
        </div>
    </div>
  </body>
</html>