<?php require 'includes/page.php'; ?>
<head>
    <title>Warnings - <?php echo $name; ?></title>
</head>
<body>
<div class="container">
    <div class="row">

        <div class="col-lg-12">
            <h1 class="page-header">Warnings</h1>
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li class="active">Warnings</li>
            </ol>
        </div>

    </div>
    <div class="row" style="margin-bottom:60px;">
        <div class="col-lg-12">
            <table class="table table-hover table-bordered table-condensed">
                <thead>
                <tr>
                    <th>
                        <div style="text-align: center;">Name</div>
                    </th>
                    <th>
                        <div style="text-align: center;">Warned By</div>
                    </th>
                    <th>
                        <div style="text-align: center;">Reason</div>
                    </th>
                    <th>
                        <div style="text-align: center;">Warned Until</div>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php
                $result = run_query($table_warnings);
                while ($row = $result->fetch_assoc()) {
                    // <<-----------------Expiration Time Converter------------>> //
                    date_default_timezone_set("UTC");
                    $expiresEpoch = $row['until'];
                    $expiresConvert = $expiresEpoch / 1000;
                    $expiresResult = date('F j, Y, g:i a', $expiresConvert);
                    ?>
                    <tr>
                        <td><?php echo "<img src='https://minotar.net/avatar/" . $row['name'] . "/25' style='margin-bottom:5px;margin-right:5px;border-radius:2px;' />" . $row['name']; ?></td>
                        <td><?php $banner = get_banner_name($row['banned_by_name']);
                            echo "<img src='https://minotar.net/avatar/" . $banner . "/25'  style='margin-bottom:5px;margin-right:5px;border-radius:2px;' />" . $banner; ?></td>
                        <td style="width: 30%;"><?php echo $row['reason']; ?></td>
                        <td>
                            <?php if ($row['until'] <= 0) {
                                $expiresResult = 'Permanent Warning';
                            }
                            if ($row['active'] == 0) {
                                $expiresResult .= ' (Inactive)';
                            }
                            echo $expiresResult;
                            ?>
                        </td>
                    </tr>
                <?php }
                $result->free();
                echo "</tbody></table>";
                ?>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</div>