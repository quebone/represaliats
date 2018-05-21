<!DOCTYPE html>
<html lang="ca">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="carles" >

    <title>Represaliats Artés</title>

    <!-- Bootstrap core CSS -->
    <link href="../dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/starter-template.css" rel="stylesheet">
  </head>

  <body>

    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
      <a class="navbar-brand" href="taula.php">Represaliats Artés</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="taula.php">Inici <span class="sr-only">(actual)</span></a>
          </li>
        </ul>
      </div>
    </nav>

    <div class="container">
    	<label for="situacio">Situació:</label>
    	<select id="situacio" onchange="changeFilterSituacio(this)">
    		<option>Represaliats franquisme</option>
    		<option>Represaliats reraguarda</option>
    		<option>Morts al front</option>
    		<option>Indeterminada</option>
    	</select>
   	</div>

    <table class="table" id="processats-consell-guerra">
		  <thead>
		    <tr>
		      <th>Cognoms, nom</th>
		      <th>Edat</th>
		      <th>Professió</th>
		      <th>Causa militar</th>
		      <th>Inici sumari</th>
		      <th>Detenció</th>
		      <th>Militància</th>
		      <th>Consell de guerra</th>
		      <th>Acusació</th>
		      <th>Pena inicial</th>
		      <th>Commutacions</th>
		      <th>Llibertat</th>
		      <th>Execució</th>
		    </tr>
		  </thead>
		  <tbody>
		    <tr>
		      <td><a href="fitxa.php">Aguilar Prat, Felip</a></td>
		      <td>33</td>
		      <td>Contramestre</td>
		      <td>000500</td>
		      <td>05-04-1940</td>
		      <td>Palma, 17-04-1934</td>
		      <td>ERC</td>
		      <td>Barcelona, 30-06-1942</td>
		      <td>Auxili a la Rebel·lió Militar</td>
		      <td>Pena de mort</td>
		      <td></td>
		      <td></td>
		      <td>Camp de la Bota, 02-10-1942</td>
		    </tr>
		    <tr>
		      <td><a href="#">Almendros Mateu, Argentina</a></td>
		      <td>24</td>
		      <td>Teixidor</td>
		      <td>008261</td>
		      <td>22-05-1939</td>
		      <td>Artés, 07-03-1939</td>
		      <td>UGT-PSUC, SRI</td>
		      <td>Barcelona, 19-06-1939</td>
		      <td>Rebel·lió Militar</td>
		      <td>Absolució</td>
		      <td></td>
		      <td>Llibertat, 19-07-1939</td>
		      <td></td>
		    </tr>
		    <tr>
		      <td><a href="#">Arroyo Arcediano, Saturnino</a></td>
		      <td>22</td>
		      <td></td>
		      <td>001602</td>
		      <td>21-08-1940</td>
		      <td>València, 07-04-1939</td>
		      <td>Militar</td>
		      <td>Barcelona, 16-07-1943</td>
		      <td>Auxili a la Rebel·lió Militar</td>
		      <td>12 anys i 1 dia</td>
		      <td>6 anys i 1 dia</td>
		      <td><p>Llibertat provisional amb expedient obert, 22-06-1940</p>
							<p>Concessió amnistia, 26-06-1986</p></td>
		      <td></td>
		    </tr>
		  </tbody>
		</table>

    <table class="table" id="morts-front">
		  <thead>
		    <tr>
		      <th>Cognoms, nom</th>
		      <th>Parella</th>
		      <th>Fills</th>
		      <th>Adreça</th>
		      <th>Data mort</th>
		      <th>Observacions</th>
		    </tr>
		  </thead>
		  <tbody>
		    <tr>
		      <td><a href="#">Altarriba Vidal, Llibori</a></td>
		      <td><a href="#">Pelfort Magem, Encarnació</a></td>
		      <td><p><a href="#">Altarriba Pelfort, Mercè</a></p>
		      	<p><a href="#">Altarriba Pelfort, Joan</a></p></td>
		      <td>C/Ample,10</td>
		      <td>15-01-1939</td>
		      <td>Mort en acció de guerra a Cervera</td>
				</tr>
			</tbody>
		</table>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="../assets/js/vendor/popper.min.js"></script>
    <script src="../dist/js/bootstrap.min.js"></script>
    <script src="js/taula.js"></script>
  </body>
</html>
