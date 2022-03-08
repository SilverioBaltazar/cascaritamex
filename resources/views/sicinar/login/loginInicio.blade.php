@extends('main')

@section('title','Iniciar Sesión')

@section('header')
@endsection

@section('content')

	{!! Form::open(['route' => 'login', 'method' => 'POST', 'id' => 'logeo']) !!}
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-8">
            
			        <div class="card-body" width="750">
						<div class="form-group row mb-0" width="750">
						    <img src="{{asset('images/logo_imej.jpg')}}" width="750" height="150">
						</div>					
						<div class="form-group row" align="center" width="750">        	
						    <h4 style="color:orange;" width="80">
						        {{ 'SISTEMA DE REGISTRO AL TORNEO ESTATAL DE FUTBOL "CASCARITA MEXIQUENSE 2022"' }}
						    </h4>
                        </div>

					    <div class="form-group row mb-0" width="650" align="right">
							<div class = "form-group row">
								<div class="col-md-6 col-form-label text-md-rigth">
									{!! Form::label('folio','* Digitar folio asignado por sistema') !!}
								</div>
								<div class="col-md-2 offset-md-0">
									{!! Form::text('folio',null,['class' => 'form-control','placeholder' => 'Folio' ,'required','minlength' => '1','maxlength' => '5']) !!}
								</div>
							</div>
							<div class = "form-group row">
								<div class="col-md-6 col-form-label text-md-right">
									{!! Form::label('usuario','* Digitar usuario') !!}
								</div>
								<div class="col-md-4 offset-md-0">
									{!! Form::email('usuario',null,['class' => 'form-control','placeholder' => 'Usuario (e-mail)' ,'required','minlength' => '6','maxlength' => '40']) !!}
								</div>
							</div>
							<div class = "form-group row">
								<div class="col-md-6 col-form-label text-md-right">
									{!! Form::label('password','* Digitar contraseña') !!}
								</div>
								<div class="col-md-4 offset-md-0">
									{!! Form::password('password',['class' => 'form-control','placeholder' => 'Contraseña','required','minlength' => '6','maxlength' => '40']) !!}
								</div>
							</div>

							@if(count($errors) > 0)
								<div class="alert alert-danger" role="alert">
									<ul>
										@foreach($errors->all() as $error)
											<li>{{ $error }}</li>
										@endforeach
									</ul>
								</div>
							@endif

							
								<div class="col-md-8 offset-md-0 text-md-center">
									{!! Form::submit('Iniciar!',['class' => 'btn btn-success']) !!}
								    <a href="{{route('autoregusu')}}" class="btn btn-primary btn_xs" title="Autoregistrse para generar un folio">
                                    <i class="fa fa-file-new-o"></i>   
                                    <span class="glyphicon glyphicon-plus"></span>Crear cuenta!!
                                    </a>	
								</div>
														                        
						</div>

                        <div class="col-md-12 offset-md-0 text-md-center">
                            <div class="col-md-0 offset-md-0 text-md-center">
                                <label class="form-check-label" style="color:gray;">
                                	Consulta nuestras redes sociales
                                	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </label>
                                <button class="btn btn-info" onclick="window.open('https://twitter.com/imej_?lang=es');"><i class="fa fa-twitter"></i> @IMEJ_</button>
                                <button class="btn btn-primary" onclick="window.open('https://www.facebook.com/imej.edomex/');"><i class="fa fa-facebook"></i> imej.edomex</button>
								<a class="btn btn-link"  onclick="window.open('http://imej.edomex.gob.mx/acerca-de/aviso-privacidad');"><i class="fa fa-info-circle"></i> Aviso de privacidad</a>
							</div> 
                        </div>

                        <div class="col-md-8 offset-md-2 text-md-center">
							<h5 style="color:green;">¿Quieres saber más sobre esta Acción Social?</h5>
							<p>Te invitamos a visitar nuestra <a href="btn btn-link" onclick="window.open('https://imej.edomex.gob.mx/')">página oficial</a>, ahí encontrarás toda la información.</p>
						</div>

						<div class="col-md-12 offset-md-2 text-md-center">
      						<b>Copyright &copy;. Derechos reservados. Secretaría de Desarrollo Social - Instituto Mexiquense de la Juventud (IMEJ).</b>
  						</div>
                    </div>

				</div>
            </div>			
		</div>

	{!! Form::close() !!}

  <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

  {!! JsValidator::formRequest('App\Http\Requests\usuarioRequest','#logeo') !!}
@endsection
