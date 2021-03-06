<?php require_once APPROOT . '/src/views/include/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <h3 id="tables">Disbursement List</h3>
                <hr />
            </div>
            <div class="bs-component">
                <table class="table table-responsive">
                    <thead>
                        <tr class="table-light">
                            <th>Detail</th>
                            <th>Status disburs</th>
                            <th>Time served</th>
                            <th>Receipt</th>
                            <th>Created at</th>
                            <th>Request</th>
                            <th>Response</th>
                        </tr>
                        <?php
                        foreach ($data as $list) {
                            echo '<tr>
                                    <td>'; ?> 
                                    <?php echo '<a  href="';?>
                                    <?= URLROOT; ?>
                                    <?php echo '/public/detail&i=' . $list->id_disburs . '">'.$list->id_disburs .'</a></td>
                                    <td>' . $list->status_disburs . '</td>
                                    <td>' . $list->time_served . '</td>
                                    <td>' . $list->receipt . '</td>
                                    <td>' . $list->created_at . '</td>
                                    <td>' . $list->request . '</td>
                                    <td>' . $list->response . '</td>
                                </tr>';
                        }

                        ?>
                </table>
            </div>
        </div>
    </div>

<?php require_once APPROOT . '/src/views/include/footer.php'; ?>
</div>