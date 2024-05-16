
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel - Users</title>
  <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

  <?php require('traderdashboardheader.php'); ?>

  <div class="container-fluid" id="main-content">
    <div class="row">
      <div class="col-lg-10 ms-auto p-4 overflow-hidden">
        <h3 class="mb-4">ORDER HISTORY</h3>

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">


            <div class="table-responsive">
              <table class="table table-hover border text-center" style="min-width: 1300px;">
                <thead>
                  <tr class="bg-dark text-light">
                    <th scope="col">S.N</th>
                    <th scope="col">Name</th>

                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody id="">
                </tbody>
              </table>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>



</body>
</html>