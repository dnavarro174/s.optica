@extends('layouts.theme')

@section('content')

    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->

    @include('layouts.nav_superior')
    <!-- end encabezado -->
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_settings-panel.html -->

        @include('layouts.menutop_setting_panel')
        <!-- end menu_user -->

            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper pt-3">
                    <div class="row justify-content-center">
                        <div class="col-md-10 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-xl-12 ">{{-- offset-md-1 --}}
                                            <div class="d-flex justify-content-between mb-4">
                                                <h4 class="card-title mb-0">Creando Boleta </h4>
                                            </div>

                                            <form class="forms-sample" id="ingresosForm2" action="{{ route('ventas.update',$comprobante->id) }}" method="post">
                                                {!! csrf_field() !!}
                                                @method("put")
                                                @include ('ventas.form')

                                            </form>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                </div>


                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
            @include('layouts.footer')
            <!-- end footer.php -->
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->


    {{-- PROVEEDORES --}}
    <div class="modal fade ass" id="Modal_add_provee" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog h-100 my-0 mx-auto d-flex flex-column justify-content-center" role="document">
            <div class="modal-content m-2">
                <form  id="f_proveedor" name="f_proveedor" method="post" action="{{ route('cli.clienteStore') }}" class="formarchivo" >
                    {!! csrf_field() !!}
                    <div class="modal-header">
                        <h5 class="modal-title text-dark" id="exampleModalLabel">Registrar Nuevo Cliente</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pt-0 form-act">


                    </div>
                    <div class="modal-footer">
                        <a href="{{route('ctas_corrientes.index', ['id'=>2])}}" target="_blank" class="btn btn-link">Ver listado</a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-dark" id="saveProveedor">Guardar</button>{{-- btnImport1 --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- fin modal --}}


@endsection
@section('footer')
    <script src="{{ asset('js/ventas.js')}}"></script>

    <link rel="stylesheet" href="{{ asset('js_auto/easy-autocomplete.min.css?id=1') }}">
    {{-- <link rel="stylesheet" href="{{ asset('js_auto/easy-autocomplete.themes.min.css') }}"> --}}

    <script src="{{ asset('js_auto/jquery.easy-autocomplete.js')}}"></script>
    <script src="{{ asset('js/autocomplete.js')}}"></script>
    <style>
        .modal-backdrop.show {
            opacity: 0.6;
        }
    </style>
    <script>
        $(document).ready(function(){
            $("#nro_preing").val('');

            $("#nro_preing").on('change', function(){
                var tipo = $("#nro_preing").val();
                console.log('click nro_preing: '+tipo);

                var $nom = $("#proyecto");
                var $label = $("#xtit");
                var $link = $("#xtit_link");
                if(tipo == "3"){

                    // seleccion MATERIALES
                    $("#doc_tipo").val('M');
                    // para ventas: mostrar Label: Cliente - caja / # Documento y caja
                    //$("#auto_conf_div").removeClass('d-none');
                    $nom.attr({'required': false, 'placeholder': 'CLIENTE'});
                    $label.text("Cliente");
                    $link.text("Registrar Cliente");

                    $("#placa").css('display','none');
                    $("#xcliente").css('display','flex');

                    $("#proyecto, #responsable").attr({'required': false});

                    $("#xcliente").attr({'required': true});
                    $("#tipo_c").attr({'required': true});
                    $("#numerodoc").attr({'required': true});

                    $('.mostrar_1').show('slow');

                }else{
                    //$("#auto_conf_div").addClass('d-none');
                    $nom.attr({'required': false, 'placeholder': 'INGRESE PLACA'});
                    $label.text("Placa de Vehículo");
                    $link.text("Registrar Vehículo");

                    $("#placa").css('display','flex');
                    $("#xcliente").css('display','none');

                    $("#proyecto, #responsable").attr({'required': true});

                    $("#xcliente").attr({'required': false});
                    $("#tipo_c").attr({'required': false});
                    $("#numerodoc").attr({'required': false});
                }

            });

        });
        @if($comprobante->tot_reg)
        reordenaFila();
        @endif


    </script>
@endsection
