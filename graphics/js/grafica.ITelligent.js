/*
    Autores:
        - Gonzalo Saavedra Postigo
        - David Mariscal Martínez
        - Aris Burgos Pintos
    Organización: ITelligent Information Technologies, S.L.
    Licencia: gpl-3.0
*/

google.load("visualization", "1", {packages:["corechart"]});
google.load("visualization", "1", {packages:["ImageChart"]});


function Grafica(tipo, contenedor, contenedorExterno){

    this.data = null;
    this.chart = null;
    this.options = null;
    this.tipo = tipo;
    this.contenedor = contenedor;
    this.contenedorExterno = contenedorExterno;

    this.draw = function(opciones) {

        this.options = opciones;

        if(this.chart == null){
            this.chart = new google.visualization[this.tipo](this.contenedor);
        }
        this.chart.draw(this.data, this.options);
    };

    this.cargarDatos = function  (columnas, filas){

        this.data = new google.visualization.DataTable();

        var longitud = columnas.length;
        for (var i = 0; i < longitud; i++){
            if(columnas[i].role != undefined){
                this.data.addColumn(columnas[i]);
            } else {
                this.data.addColumn(columnas[i].tipo, columnas[i].nombre);
            }
        }
        this.data.addRows(filas);

        this.redraw();

    };

      this.modificarFilas = function (filas){
        if(this.data != null){
            this.data.removeRows(0,this.data.getNumberOfRows());
            this.data.addRows(filas);
        }

        this.redraw();
      };

      this.modificarFilaDatos = function  (fila, filanueva)
      {
        for (var i = 0; i < this.data.getNumberOfColumns(); i++) {
            this.data.setValue(fila,i,filanueva[i]);
        }

        this.redraw();
      };

        this.redraw = function(){
            if(this.chart != null){
                this.chart.draw(this.data, this.options);
            }
        };

    this.generargraficaLine = function (titulo,min,max,datosgraf,blnDispercion,blnLimites,valorLimites)
    {

        var mediaTotal = 0;
        var sumCuadradosTotal = 0;
        var numElementos = 0;
        var puntosMedia = [];
        var numElementosGrupo = 0;
        var sumcuadradosGrupo = 0;
        var desviacionGrupo = [];
        for (var i = 0; i < datosgraf.length; i++){
            puntosMedia[i]  = 0;
            sumcuadradosGrupo = 0;
            numElementosGrupo = 0;
            for(var j = 1; j < datosgraf[i].length; j++){
                numElementos = numElementos + 1;
                numElementosGrupo = numElementosGrupo + 1;
                mediaTotal = mediaTotal + datosgraf[i][j];
                sumCuadradosTotal = sumCuadradosTotal + (datosgraf[i][j] * datosgraf[i][j]);
                puntosMedia[i] = puntosMedia[i] + datosgraf[i][j];
                sumcuadradosGrupo = sumcuadradosGrupo + datosgraf[i][j] * datosgraf[i][j];
            }

            puntosMedia[i] = puntosMedia[i] / numElementosGrupo;
            desviacionGrupo[i] = Math.sqrt(sumcuadradosGrupo - numElementosGrupo * puntosMedia[i] * puntosMedia[i]) / (numElementosGrupo - 1);

        }

        mediaTotal = mediaTotal / numElementos;

        var desviacionTotal = Math.sqrt(sumCuadradosTotal - numElementos * mediaTotal * mediaTotal) / (numElementos - 1);

        var k = valorLimites;
        var lm = mediaTotal, ls = Math.min(mediaTotal + (desviacionTotal * k),100), li = Math.max(mediaTotal - (desviacionTotal * k), 0);

        // Creamos estructuras de pintado.
        var columnas = [];

        columnas.push({tipo:'string', nombre:'Evaluador'});
        columnas.push({tipo:'number', nombre:'Puntuacion'});

        if(blnDispercion){
            columnas.push({type:'number', role:'interval'});
            columnas.push({type:'number', role:'interval'});
        }

        if (blnLimites){
            columnas.push({tipo:'number', nombre:'LS'});
            columnas.push({tipo:'number', nombre:'LM'});
            columnas.push({tipo:'number', nombre:'LI'});
        }

        var datos = [];
        var KD = 1;

        for (var i = 0; i < datosgraf.length; i++){

            datos[i] = [];
            datos[i].push(datosgraf[i][0]);
            datos[i].push(to2Decimales(puntosMedia[i]));

            if(blnDispercion){
                if(isNaN(desviacionGrupo[i])){
                    datos[i].push(to2Decimales(Math.max(puntosMedia[i],0)));
                    datos[i].push(to2Decimales(Math.min(puntosMedia[i],100)));
                }
                else{
                    datos[i].push(to2Decimales(Math.max(puntosMedia[i] - (desviacionGrupo[i] * KD),0)));
                    datos[i].push(to2Decimales(Math.min(puntosMedia[i] + (desviacionGrupo[i] * KD),100)));
                }
            }

            if (blnLimites){
                datos[i].push(to2Decimales(ls));
                datos[i].push(to2Decimales(lm));
                datos[i].push(to2Decimales(li));
            }

        }

        function to2Decimales(num){
            return parseFloat(num.toFixed(2));
        }

        this.cargarDatos(columnas,datos);

        // Si es barChart recalculamos la altura.
        if(this.tipo == 'BarChart')
        {

            var altura_top = 75;
            var altura_grafica = (datosgraf.length * 20);
            var altura_down = 100;
            var altura_total = altura_top + altura_grafica + altura_down;

            this.draw(
            {
                // Height: document.getElementById('grafica').offsetHeight.

                height:  altura_total,
                width: (this.contenedorExterno.offsetWidth * 0.90),
                chartArea:{left:75,top:altura_top,width:"75%",height: altura_total - altura_down},
                title: titulo,
                max: max,
                min: min,
                // Animation:{duration:500, easing:'in'}.
                colors: ['#0000ff', '#81f781', '#f7d358', '#fa5858']
              });
        }else
        {
                this.draw(
                {
                    // Height: document.getElementById('grafica').offsetHeight.

                    height: (this.contenedorExterno.offsetHeight * 0.90),
                    width: (this.contenedorExterno.offsetWidth * 0.90),
                    chartArea:{left:75,top:75,width:"75%"},
                    title: titulo,
                    max: max,
                    min: min,
                    // Animation:{duration:500, easing:'in'}.
                    colors: ['#0000ff', '#81f781', '#f7d358', '#fa5858']
                });
        }

    }

    this.generargraficabox = function (titulo,min,max,datosgraf)
    {

        var medianas = [];
        var cuartiles1 = [];
        var cuartiles3 = [];
        var maximos = [];
        var minimos = [];

        var blnGrupoSinDato = false;
        var blnGrupoUnDato = [];

        for (var i = 0; i < datosgraf.length; i++){

            blnGrupoUnDato[i] = false;
            var datosord = datosgraf[i].concat();

            datosord.splice(0,1);

            datosord = datosord.sort(function(a,b){return a - b});

            var tam = datosord.length;

            // Si no tiene datos se actualiza booleano para linea.

            if(tam == 0)
            {
                blnGrupoSinDato = true;
            }

            if(tam == 1) {
                blnGrupoUnDato[i] = true;
            }

            minimos[i] = datosord[0];
            maximos[i] = datosord[tam - 1];

            // Quitamos uno porque vector comienza en 0.
            var poscuartil1 = Math.ceil(1 * tam / 4 ) - 1;
            var posmediana = Math.ceil(2 * tam / 4) - 1;
            var poscuartil3 = Math.ceil(3 * tam / 4) - 1;

            cuartiles1[i] = datosord[poscuartil1];
            medianas[i] = datosord[posmediana];
            cuartiles3[i] = datosord[poscuartil3];
        }

        var tam = datosgraf.length

        this.data = new google.visualization.DataTable();

        // Total filas (nº de cajas + 2 para extremos).
        var numfilas = tam + 2;
        this.data.addRows(numfilas);

        // Total columnas 5 (Mediana, Minimo, cuartil1, cuartil2, maximo).
        var numColumnas = 6;
        for (var i = 0; i < numColumnas; i++){
            this.data.addColumn('number');
        }

        var strejex = '0:||';
        var strhdls = '1|';
        var strchds = min + ',' + max + ',';// Limites.
        var indicedatos = 0;

        // Relleno de -1 inicial.
        for (var j = 0; j < numColumnas; j++){
            this.data.setValue(indicedatos, j, -1)
        }

        indicedatos += 1;

        for (var i = 0; i < tam; i++){

            strejex += datosgraf[i][0] + '|';
            strhdls += '1|';
            strchds += min + ',' + max + ',';

            // Punto.
            if(blnGrupoUnDato[i] == true) {
                // Minimo.
                this.data.setValue(indicedatos, 0, -1);

                // Cuartil1.
                this.data.setValue(indicedatos, 1, -1);

                // Cuartil3.
                this.data.setValue(indicedatos, 2, -1);

                // Maximo.
                this.data.setValue(indicedatos, 3, -1);

                // Mediana.
                this.data.setValue(indicedatos, 4, -1);

                // Punto.
                this.data.setValue(indicedatos, 5, medianas[i]);
            } else{
                // Minimo.
                this.data.setValue(indicedatos, 0, minimos[i]);

                // Cuartil1.
                this.data.setValue(indicedatos, 1, cuartiles1[i]);

                // Cuartil3.
                this.data.setValue(indicedatos, 2, cuartiles3[i]);

                // Maximo.
                this.data.setValue(indicedatos, 3, maximos[i]);

                // Mediana.
                this.data.setValue(indicedatos, 4, medianas[i]);

                // Punto.
                this.data.setValue(indicedatos, 5, -1);

            }
            indicedatos += 1;
        }

        strhdls += '1';
        strchds += min + ',' + max;
        // Relleno de -1 final.
        for (var j = 0; j < numColumnas; j++){
            this.data.setValue(indicedatos, j, -1)
        }

        // Si hay grupo sin datos no se pinta linea.
        var strPintaLinea = '';
        if(blnGrupoSinDato == true) {
            strPintaLinea = '0,5F5F5F,13,0,t,FFFFFF';
        }

        this.draw(
        {

            chxl:strejex, // 0:||a|b|j|j|', // Eje de las x (El primero y último vacío).
            chxt:'x,y', // Mostrar ejes.
            // chxs:strPintaLinea, // Pintar linea.
            // chs: contenedor.offsetWidth + 'x' + contenedor.offsetHeight, // Ancho y altura ¿mirar xq se minimiza el div?
            width: (this.contenedorExterno.offsetWidth * 0.90),
            hight: (this.contenedorExterno.offsetHeight * 0.90),
            // Chco: 'FFFFFF', // Color de barra blanco para que no se vean líneas.
            cht:'lc', // Gráfico de línas.
            chds:strchds,// '0,100,0,100,0,100,5,95',    // Limites
            // chd:'t0:-1,20,26,15,25,-1|-1,5,10,7,12,-1|-1,25,45,47,24,-1|-1,40,30,27,39,-1|-1,63,80,59,80,-1',
            // No se usa, se pone en datetable.
            chls:strhdls, // 1|1|1|1|1.
            chartArea:{left:75, top:75, width:"75%"},
            chm:'F,0000FF,0,1:' + (numfilas - 1) + ',20|H,FF0000,0,0:' + (numfilas - 1) + ',1:20|H,0CBF0B,3,1:' +
            (numfilas - 1) + ',1:20|H,000000,4,1:' + (numfilas - 1) + ',1:20|o,FF8C00,5,1:' + (numfilas - 1) + ',10',
            chdl:'||||||Mayor Valor|Caja y Bigotes|Mediana|Menor Valor|Nota Individual',
            chco:'FFFFFF,FFFFFF,FFFFFF,FFFFFF,FFFFFF,FFFFFF,0CBF0B,0000FF,000000,FF0000,FF8C00',
            // Caja de la fila 0 a las 4 en adelant, tomando los intermedios.
            chtt:titulo // Título.

        });
    }
}