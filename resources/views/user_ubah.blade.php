<!DOCTYPE html>
<html>
<head>
    <title>Form Ubah Data User</title>
</head>
<body>
    <h1>Form Ubah Data User</h1>
    <a href="{{ url('/user') }}">Kembali</a>
    <br><br>

    <form method="post" action="{{ url('/user/ubah_simpan/' . $data->user_id) }}">

        {{ csrf_field() }}
        {{ method_field('PUT') }}

        <label>Username</label>
        <input type="text" name="username" value="{{ $data->username }}">
        <br>

        <label>Nama</label>
        <input type="text" name="nama" value="{{ $data->username}}">
        <br>

        <label>Password</label>
        <input type="password" name="password" value="{{ $data->password }}">
        <br>

        <label>Level ID</label>
        <input type="number" name="level_id" value="{{ $data->level_id }}">
        <br><br>

        <input type="submit" value="Ubah">
    </form>
</body>
</html>