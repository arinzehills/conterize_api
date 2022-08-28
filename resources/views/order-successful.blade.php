<!doctype html>
<html lang="en">
  <head>
    <title>Successful Order | Conterize</title>
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
                <h3> Successfull Order Made </h3>
                <p> Hey, </p>
                <p> You have successfully order a {{$request_type}} content with the request name of "{{$request_name}}"" </p>
                <p> Continue enjoying Conterize <br> </p>
                <br />
                
                <p> Best Regards</p>
                <p> Team, Conterize </p>
            </div>
        </div>
    </div>
  </body>
</html>