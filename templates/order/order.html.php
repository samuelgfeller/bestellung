<?php require_once __DIR__ . '/../base.html.php'; ?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<h2 style="font-weight: normal;"><b><?php echo $client->getFirstName() . ' ' . $client->getName() ?></b><br>Bestellung für
    den <b> <?= $GETDateText ?> </b>
</h2>
<button class="ownBtn otherDateBtn" id="changeOrderDateBtn">Bestelldatum ändern</button>

<div class="search">
    <input type="text" id="searchInput" autocomplete="off" placeholder="Artikel suchen"
           onkeyup="filter('orderArticleTable',0  )">
</div><br>
<form action="success" method="post" id="orderForm" onkeypress="return event.keyCode != 13;">

    <!--    Set date outside the foreach loop -->
    <input type="hidden" name="date" value="<?= $GETDateSQL ?>">
    <!--    set the order id -->
    <input type="hidden" name="order_id" value="<?= $order_id ?>">

    <table class="items article" id="orderArticleTable">
        <tr>
            <th>Name</th>
            <th>Kg Preis</th>
            <th>Verfügbar</th>
            <th align="right">Anzahl Päckchen</th>
            <th></th>
            <th>Gewicht pro Päckchen oder Anzahl Stücke</th>
            <th>Besonderheiten</th>
            <th>Gesamtgewicht (g)</th>

        </tr>
        <?php
        if ($articleAndOrderPositions) {
            foreach ($articleAndOrderPositions as $articleAndOrderPosition) {
                // Initialise variables
                $ba = $articleAndOrderPosition['order_article'];
                $position = $articleAndOrderPosition['already_ordered'];
                $possibilities = $articleAndOrderPosition['order_possibilities'];
                // Set order_article_id
                $baId = $ba->getOrderArticleId();
                $pieceWeight = $ba->getPieceWeight();

                $pAnz = null;
                $weight = null;
                $comment = null;
                if ($position) {
                    $pAnz = empty($position->getPackageAmount()) ? null : $position->getPackageAmount();
                    $weight = empty($position->getWeight()) ? null : $position->getWeight();
                    $comment = $position->getComment();
                }

                ?>

                <tr id="order_article<?= $baId ?>">
                    <td><?= !empty($pieceWeight) ? $ba->getName() . ' (Stk. <b>ca.</b> ' . $pieceWeight . 'g.)' : $ba->getName() ?></td>
                    <td><?= $ba->getKgPrice() ?></td>
                    <td id="availableWeight<?= $baId ?>" class="availableWeight">
                        <span><?= $ba->getAvailableWeight(); ?></span> kg
                    </td>
                    <td class="amountNumber"><input id="pAmount<?= $baId ?>" class="comment pAmount" type="number"
                                                    placeholder="0" min="0" value="<?= $pAnz ?>"
                                                    data-baid="<?= $baId ?>"
                                                    max="15" name="pAmount[]"></td>
                    <td id="timesTd<?= $baId ?>">&times;</td>
                    <td style="width:350px;">
                        <div id="calcInfo<?= $baId ?>" class="calcInfoClass" data-baid="<?= $baId ?>">
                            <?php
                            foreach ($possibilities as $possibility) {
                                if (!empty($possibility)) { ?>
                                    <div class="check-button">
                                        <label>
                                            <input class="weightCheckbox weightInput<?= $baId ?>"
                                                   type="checkbox"
                                                   value="<?= $possibility ?>"
                                                   data-baid="<?= $baId ?>"
                                                <?= $weight == $possibility ? 'checked' : '' ?>
                                            >
                                            <span><?= $possibility > 15 ? $possibility . 'g.' : $possibility . ' Stk. ' ?></span>
                                        </label>
                                    </div>
                                <?php }
                            } ?>
                            <!--  Unchecked inputs are not sent to server and an Array with all indexes (empty string as value if nothing)
                            The value is filled with javascript on the checkbox listener-->
                            <input type="hidden" id="singleWeight<?= $baId ?>" name="singleWeight[]"
                                   value="<?= $weight == $possibility ? $possibility : '' ?>">
                        </div>
                        <!-- Inserting the hidden info here because outide it affects the nth:child(even) -->
                        <input type="hidden" name="ba_id[]" value="<?= $baId ?>">
                    </td>
                    <td><input class="comment" type="text" maxlength="200" value="<?= $comment ?>" name="comment[]">
                    </td>
                    <td id="outputWeight<?= $baId ?>">
                    </td>
                </tr>

                <?php
            }
        } else {
            echo '<h3><b style="color:red">Es sind keine Daten vorhanden für dieses Datum.</b></h3>';
        } ?>
    </table>
    <a class="btn helpBtn" href="help" style="text-align: left;"><i class="glyphicon glyphicon-question-sign"></i> Anleitung</a>
    <textarea name="remark" class="remarkInput" cols="60" rows="1" placeholder="Bemerkung zur Bestellung"><?= $order->getRemark(); ?></textarea>
    <input type="submit" value="Bestellen">
</form>
<div class="emptyFooter">
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modal Header</h4>
            </div>
            <div class="modal-body">
                <p>Some text in the modal.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Schliessen</button>
            </div>
        </div>

    </div>
</div>
