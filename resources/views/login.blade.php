<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    {{-- เข้าสู่ระบบ --}}
    <form action="users" method="POST">
        @csrf
        <label for="">เข้าสู่ระบบ</label>
        <input type="tel" name="phone" id="">
        <input type="password" name="pass" id="">
        <input type="hidden" name="type" value="3"> {{-- 1 แอดมิน 2 ผู้ใช้คาร์แคร์ 3 ผู้ใช้ลูกค้า --}}
        <button type="submit">[เช็คข้อมูล]</button>
    </form>
    {{-- สมัคร --}}
    <form action="create_user" method="POST">
        @csrf
        <label for="">สมัครสมาชิก</label>
       เบอร์ <input type="tel" name="phone" id="">
        รหัสผ่าน<input type="password" name="pass" id="">

        <input type="hidden" name="type" value="3"> {{-- แอดมิน 2 ผู้ใช้คาร์แคร์ 3 ผู้ใช้ลูกค้า --}}
        <button type="submit">[เช็คข้อมูล]</button>
    </form>

</body>
</html>
