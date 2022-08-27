<!doctype html>
<html lang="en">
  <head>
    <title>Demo Request </title>
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
                <h3> A Demo request has been placed by a client</h3>
                <p> Hey, </p>
                <p> A user with the following credentials have requested a demo </p>
                <br />
                <b>First name:<b> {{$firstname}}
                <br/>
                <br />
                <b>Last name:<b> {{$lastname}}
                <br/>
                <br />
                <b>Email:<b> {{$email}}
                <br/>
                <br />
                <b>Phone:<b> {{$phone}}
                <br/>
                <br />
                <b>Content Types:<b> {{$content_types}}
                <br/>
                <b>Industry:<b> {{$industry}}  
                    <br />
                <b>Company Size:<b> {{$companysize}}
                <br/>
                <br />
                <b>Details Description:<b> {{$description}}
                <br/> 
                <br/>
                <p> Best Regards</p>
                <p> Team, Conterize </p>
            </div>
        </div>
    </div>
  </body>
</html>