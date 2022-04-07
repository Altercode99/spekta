<!doctype html>
  
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="<?= asset("img/spekta.png") ?>" type="image/x-icon" />
  <title>S.P.E.K.T.A PDF Reader</title>
  <script src="<?= asset('js/pdf.min.js') ?>">
  </script>

  <style>
      * {
          margin: 0;
          overflow:hidden;
      }

      #canvas_container {
          width: 99.9%;
          height: 90vh;
          overflow: auto;
          overflow-x: hidden;
      }
 
      #canvas_container {
        background: #333;
        text-align: center;
        border: solid 3px;
      }

      .nav_container {
          display: flex;
          flex-direction: row;
          justify-content: space-around;
          align-items: center;
          padding: 20px;
      }

      #current_page {
          width: 40px;
      }

      .wmark {
          position: absolute;
          bottom: 0;
          left: 0;
      }

      #warning {
           display: flex;
           flex-direction: column;
           width: 100%;
           height: 100vh;
           justify-content: center;
           align-items: center;
           text-align: center;
      }

      @media print {
      	#my_pdf_viewer {
      	   display: none;
      	}
      }
  </style>
</head>
<body>
    <?php 
        $ip = explode('.', $this->input->ip_address()); 
        if($ip[0] != "::1") {
            $segment = $ip[0].'.'.$ip[1];
        } else {
            $segment = '';
        }
    ?>
    <?php if($this->auth->role == 'admin' || $segment == '10.9' || $segment == '192.168') { ?>
     <div id="my_pdf_viewer">
        <div id="canvas_container">
            <canvas id="pdf_renderer" style="margin-left: -6px"></canvas>
        </div>

        <div class="nav_container" style="background-color: #116171">
            <div id="navigation_controls">
                <button id="go_previous">Previous</button>
                <input id="current_page" value="1" type="number"/>
                <button id="go_next">Next</button>
            </div>
            <?php if($mode == 'read') { ?>
            <p style='font-style:italic;color:#fff'>Developed By Hardware & Network</p>
            <?php } ?>
            <div id="zoom_controls">  
                <span style="color:#fff">Zoom</span>
                <button id="zoom_in">+</button>
                <button id="zoom_out">-</button>
            </div>
        </div>
    </div>
    <?php } else { ?>
        <div id="warning">
            <h5>Hanya bisa di buka dari Jaringan PT. Kimia Farma Tbk. Plant Jakarta</h5>
        </div>
    <?php } ?>
 
    <script>
        document.addEventListener('contextmenu', event => event.preventDefault());

        var myState = {
            pdf: null,
            currentPage: 1,
            zoom: 1
        }

        var file = '<?= $file ?>';
        var mode = '<?= $mode ?>';
      
        pdfjsLib.getDocument('./assets/files/' + file).then((pdf) => {
            myState.pdf = pdf;
            render();
        });
 
        function render() {
            myState.pdf.getPage(myState.currentPage).then((page) => {
          
                var canvas = document.getElementById("pdf_renderer");
                var ctx = canvas.getContext('2d');
      
                var viewport = page.getViewport(myState.zoom);
 
                canvas.width = viewport.width;
                canvas.height = viewport.height;
          
                page.render({
                    canvasContext: ctx,
                    viewport: viewport
                });
            });
        }
 
        document.getElementById('go_previous').addEventListener('click', (e) => {
            if(myState.pdf == null || myState.currentPage == 1) 
              return;
            myState.currentPage -= 1;
            document.getElementById("current_page").value = myState.currentPage;
            render();
        });
 
        document.getElementById('go_next').addEventListener('click', (e) => {
            if(myState.pdf == null || myState.currentPage > myState.pdf._pdfInfo.numPages) 
               return;
            myState.currentPage += 1;
            document.getElementById("current_page").value = myState.currentPage;
            render();
        });
 
        document.getElementById('current_page').addEventListener('keypress', (e) => {
            if(myState.pdf == null) return;
          
            // Get key code
            var code = (e.keyCode ? e.keyCode : e.which);
          
            // If key code matches that of the Enter key
            if(code == 13) {
                var desiredPage = 
                document.getElementById('current_page').valueAsNumber;
                                  
                if(desiredPage >= 1 && desiredPage <= myState.pdf._pdfInfo.numPages) {
                    myState.currentPage = desiredPage;
                    document.getElementById("current_page").value = desiredPage;
                    render();
                }
            }
        });
 
        document.getElementById('zoom_in').addEventListener('click', (e) => {
            if(myState.pdf == null) return;
            myState.zoom += 0.5;
            render();
        });
 
        document.getElementById('zoom_out').addEventListener('click', (e) => {
            if(myState.pdf == null) return;
            myState.zoom -= 0.5;
            render();
        });

        if(mode == 'preview') {
            myState.zoom -= 0.5;
        }
    </script>
</body>
</html>