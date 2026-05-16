<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">

<title>ADMIN DASHBOARD</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial;
}

body{
    background:#f1f5f9;
    padding:30px;
}

.container{
    width:1200px;
    margin:auto;
}

.cards{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
    margin-bottom:30px;
}

.card{
    background:white;
    padding:30px;
    border-radius:15px;
}

.card h2{
    font-size:40px;
    margin-top:10px;
}

.table{
    background:white;
    padding:30px;
    border-radius:15px;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th,
table td{
    padding:15px;
    border-bottom:1px solid #ddd;
    text-align:left;
}

button{
    background:red;
    color:white;
    border:none;
    padding:10px 15px;
    border-radius:8px;
    cursor:pointer;
}

.status{
    padding:5px 10px;
    border-radius:8px;
    color:white;
}

.waiting{
    background:orange;
}

.helping{
    background:blue;
}

.completed{
    background:green;
}

</style>

</head>
<body>

<div class="container">

    <h1>
        🚨 ADMIN DASHBOARD
    </h1>

    <br><br>

    <div class="cards">

        <div class="card">

            <h3>
                Tổng SOS
            </h3>

            <h2>
                <?= $totalSOS['total']; ?>
            </h2>

        </div>

        <div class="card">

            <h3>
                SOS hoạt động
            </h3>

            <h2>
                <?= $activeSOS['total']; ?>
            </h2>

        </div>

        <div class="card">

            <h3>
                Users
            </h3>

            <h2>
                <?= $totalUsers['total']; ?>
            </h2>

        </div>

    </div>

    <div class="table">

        <h2>
            Danh sách SOS
        </h2>

        <br>

       <table>

    <tr>

        <th>ID</th>

        <th>Người gửi</th>

        <th>Nội dung</th>

        <th>Status</th>

        <th>Thời gian</th>

        <th>Action</th>

    </tr>

    <?php if(!empty($sosList)): ?>

        <?php foreach($sosList as $item): ?>

            <tr>

                <td>
                    <?= $item['id']; ?>
                </td>

                <td>
                    <?= $item['name']; ?>
                </td>

                <td>
                    <?= $item['message']; ?>
                </td>

                <td>

                    <span class="
                        status
                        <?= $item['status']; ?>
                    ">

                        <?= $item['status']; ?>

                    </span>

                </td>

                <td>
                    <?= $item['created_at']; ?>
                </td>

                <td>

                    <form
                        method="POST"
                        action="/2026_SOS/public/admin/delete-sos"
                    >

                        <input
                            type="hidden"
                            name="id"
                            value="<?= $item['id']; ?>"
                        >

                        <button type="submit">

                            Xóa

                        </button>

                    </form>

                </td>

            </tr>

        <?php endforeach; ?>

    <?php else: ?>

        <tr>

            <td colspan="6">

                Không có dữ liệu SOS

            </td>

        </tr>

    <?php endif; ?>

</table>

    </div>

</div>

</body>
</html>