<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <title>Login - Farmácia</title>
      
      <!-- Favicon -->
      <link rel="shortcut icon" href="{{asset('images/favicon.ico')}}" />
      <link rel="stylesheet" href="{{asset('css/backend-plugin.min.css')}}">
      <link rel="stylesheet" href="{{asset('css/backend.css')}}?v=1.0.0">
      <link rel="stylesheet" href="{{asset('vendor/@fortawesome/fontawesome-free/css/all.min.css')}}">
      <link rel="stylesheet" href="{{asset('vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css')}}">
      <link rel="stylesheet" href="{{asset('vendor/remixicon/fonts/remixicon.css')}}">  </head>
      <style>
        .wrapper{
            background-image: url('images/bg.jpg');
            background-size: cover;
            background-repeat: no-repeat
        }
      </style>
  <body class="  ">
    <div class="wrapper" >
        <section class="login-content">
           <div class="container">
              <div class="row align-items-center justify-content-center height-self-center">
                 <div class="col-lg-8">
                    <div class="card auth-card">
                       <div class="card-body p-0">
                          <div class="d-flex align-items-center auth-content">
                             <div class="col-lg-7 align-self-center">
                                <div class="p-3">
                                   <h2 class="mb-2">Login</h2>
                                   <form action="{{ route('funcionario.login') }}" method="post">
                                    @csrf
                                      <div class="row">
                                         <div class="col-lg-12">
                                            <div class="floating-label form-group">
                                               <input class="floating-input form-control" type="text" 
                                               name="email" placeholder=" "
                                               value="{{ old('email') }}" required autofocus>
                                                 @error('email')
                                                      <div style="color: red;">{{ $message }}</div>
                                                @enderror
                                             <label>E-mail</label>
                                            </div>
                                         </div>
                                         <div class="col-lg-12">
                                            <div class="floating-label form-group">
                                               <input class="floating-input form-control" name="senha" type="password" placeholder=" ">
                                               <label>Senha</label>
                                                @error('senha')
                                                      <div style="color: red;">{{ $message }}</div>
                                                @enderror
                                            </div>
                                         </div>
                                      </div>
                                      <button type="submit" class="btn btn-primary">Entrar</button>
                                   </form>
                                </div>
                             </div>
                             <div class="col-lg-5 content-right">
                                <img src="{{asset('images/login/01.png')}}" class="img-fluid image-right" alt="">
                             </div>
                          </div>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
        </section>
        </div>
      
    <!-- Backend Bundle JavaScript -->
    <script src="{{asset('js/backend-bundle.min.js')}}"></script>
    
    <!-- Table Treeview JavaScript -->
    <script src="{{asset('js/table-treeview.js')}}"></script>
    
    <!-- Chart Custom JavaScript -->
    <script src="{{asset('js/customizer.js')}}"></script>
    
    <!-- Chart Custom JavaScript -->
    <script async src="{{asset('js/chart-custom.js')}}"></script>
    
    <!-- app JavaScript -->
    <script src="{{asset('js/app.js')}}"></script>
    <script>
        $(function(){
          $('.alert').fadeOut(5000)
        })
      </script>
  </body>
</html>
