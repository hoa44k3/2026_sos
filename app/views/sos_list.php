<?php

require_once "../app/models/Helper.php";

$helperModel = new Helper();

?>

<?php if(!empty($sosList)): ?>

    <?php foreach($sosList as $item): ?>

        <?php

        $helpers = $helperModel->getHelpersBySOS(
            $item['id']
        );

        ?>

        <div class="item">

            <div class="danger">

                🚨 <?= $item['name']; ?>

            </div>

            <p>
                <?= $item['message']; ?>
            </p>

            <div class="small">

                📏 Khoảng cách:
                <?= round($item['distance'], 2); ?> km

                <br><br>

                👥 Người hỗ trợ:
                <?= $item['helper_count']; ?>

                <br><br>

                Status:
                <?= $item['status']; ?>

                <br><br>

                <?= $item['created_at']; ?>

            </div>

            <br>

            <a
                target="_blank"
                href="https://www.openstreetmap.org/?mlat=<?= $item['latitude']; ?>,<?= $item['longitude']; ?>"
            >
                📍 Chỉ đường
            </a>

            <br><br>

            <?php if(
                $item['status'] != 'completed'
            ): ?>

                <form
                    method="POST"
                    action="/2026_SOS/public/sos/join"
                >

                    <input
                        type="hidden"
                        name="sos_id"
                        value="<?= $item['id']; ?>"
                    >

                    <button type="submit">

                        🙋 TÔI SẼ GIÚP

                    </button>

                </form>

            <?php endif; ?>

            <br>

            <?php if(
                $_SESSION['user']['id']
                == $item['user_id']
                &&
                $item['status']
                != 'completed'
            ): ?>

                <form
                    method="POST"
                    action="/2026_SOS/public/sos/complete"
                >

                    <input
                        type="hidden"
                        name="sos_id"
                        value="<?= $item['id']; ?>"
                    >

                    <button
                        style="background:green;"
                        type="submit"
                    >

                        ✅ ĐÃ AN TOÀN

                    </button>

                </form>

            <?php endif; ?>

            <br>

            <?php if(!empty($helpers)): ?>

                <strong>
                    Người đang tới hỗ trợ:
                </strong>

                <br><br>

                <?php foreach($helpers as $helper): ?>

                    🙋 <?= $helper['name']; ?>

                    <br>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>

    <?php endforeach; ?>

<?php else: ?>

    <div class="item">

        Không có SOS gần bạn

    </div>

<?php endif; ?>