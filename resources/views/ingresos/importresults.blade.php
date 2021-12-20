@extends('layouts.theme')
@section('content')

<div class="main-panel" style="background:#FFFFFF">
        
        <div class="content-wrapper pt-0"  style="background:#FFFFFF">
          <div class="card" style="width:100%;background:#FFFFFF; border:none">
            <div class="card-body">
              <h4 class="card-title">Resultado de Importaci&oacute;n</h4>
              <div class="row">
                <div class="col-12">
                    <table class="table dataTable no-footer" role="grid" aria-describedby="order-listing_info" border="0">
                      <thead>
                          <tr role="row">
                            <th></th> 
                            <?php if($lista[0]["codigo"]){ ?><th>CODIGO</th><?php } ?>
                            <?php if($lista[0]["nombre"]){ ?><th>NOMBRE PROGRAMA</th><?php } ?>
                            <?php if($lista[0]["tipo"]){ ?><th>TIPO</th><?php } ?>
                            <?php if($lista[0]["modalidad"]){ ?><th>MODALIDAD</th><?php } ?>
                            <?php if($lista[0]["nombre_curso"]){ ?><th>NOMBRE CURSO</th><?php } ?>                  
                            <?php if($lista[0]["area_tematica"]){ ?><th>AREA TEMATICA</th><?php } ?>
                            <?php if($lista[0]["docente"]){ ?><th>DOCENTE</th><?php } ?>
                            <?php if($lista[0]["aula"]){ ?><th>AULA</th><?php } ?>
                            <?php if($lista[0]["piso"]){ ?><th>PISO</th><?php } ?>
                            <?php if($lista[0]["nsesiones"]){ ?><th>N SESIONES</th><?php } ?>
                            <?php if($lista[0]["fecha_desde"]){ ?><th>FECHA DESDE</th><?php } ?>
                            <?php if($lista[0]["fecha_hasta"]){ ?><th>FECHA HASTA</th><?php } ?>
                            <?php if($lista[0]["frecuencia"]){ ?><th>FRECUENCIA</th><?php } ?>
                            <?php if($lista[0]["estado"]){ ?><th>ESTADO</th><?php } ?>
                            <?php //if($vEnt!=0){ ?>
                            
                            <?php //} ?>
                          </tr>

                      </thead>
                    <tbody>                       
                      <?php foreach($lista as $lst){?>
                            <tr>
                              <td><?php echo $lst->mensaje; ?> </td>
                              <?php if($lst->codigo!=""){ ?><td><?php echo $lst->codigo; ?> </td><?php } ?>
                              <?php if($lst->nombre!=""){ ?><td><?php echo $lst->nombre; ?> </td><?php } ?>
                              <?php if($lst->tipo!=""){ ?><td><?php echo $lst->tipo; ?> </td><?php } ?>
                              <?php if($lst->modalidad!=""){ ?><td><?php echo $lst->modalidad; ?> </td><?php } ?>
                              <?php if($lst->nombre_curso!=""){ ?><td><?php echo $lst->nombre_curso; ?> </td><?php } ?>
                              <?php if($lst->area_tematica!=""){ ?><td><?php echo $lst->area_tematica; ?> </td><?php } ?>
                              <?php if($lst->docente!=""){ ?><td><?php echo $lst->docente; ?> </td><?php } ?>
                              <?php if($lst->aula!=""){ ?><td><?php echo $lst->aula; ?> </td><?php } ?>
                              <?php if($lst->piso!=""){ ?><td><?php echo $lst->piso; ?> </td><?php } ?>
                              <?php if($lst->nsesiones!=""){ ?><td><?php echo $lst->nsesiones; ?> </td><?php } ?>
                              <?php if($lst->fecha_desde!=""){ ?><td><?php echo $lst->fecha_desde; ?> </td><?php } ?>
                              <?php if($lst->fecha_hasta!=""){ ?><td><?php echo $lst->fecha_hasta; ?> </td><?php } ?>
                              <?php if($lst->frecuencia!=""){ ?><td><?php echo $lst->frecuencia; ?> </td><?php } ?>
                              <?php if($lst->estado!=""){ ?><td><?php echo $lst->estado; ?> </td><?php } ?>
                            </tr>                          
                          
                     <?php }?>
                    </tbody>
                  </table>


                </div>
              </div>
            </div>
          </div>
        </div> 
      </div>