<!doctype html>
<html lang="en">
  <head>
    <title>Request has been Assign to You | Conterize</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body>

    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-sm-12 m-auto">
                <h3>Request with name {{$request_name}}</h3>
                <p> Hey, </p>
                <p> A content request has been assign to you by the admin</p>
                <p> These request comes to you do to the fact that you a content creator at conterize <br> </p>
                <p>You can login using these credentials </p>
                <br/>
                <b>Request Name:<b> {{$request_name}}
                <br/>
                <br/>
                <br/>
                <a class='button' href={{env('MAIL_DOMAIN_ADDRESS')."/dashboard/request"}}
                    style="color:white;background: rgb(146, 3, 255);
                        border-radius: 10px;padding:1rem;
                        text-decoration: none;
                        border: none;">View
                </a>
                <br/>
                <p> Best Regards</p>
                <p> Team, Conterize </p>
            </div>
        </div>
    </div>
  </body>
</html>