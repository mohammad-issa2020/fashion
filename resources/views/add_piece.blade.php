<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<form method="POST" action="{{route('store_piece')}}">
    @csrf
  <div class="form-group">
    <label for="exampleInputEmail1">Name </label>
    <input type="text" class="form-control" name ="name" placeholder="Name">
  </div>
   <button type="submit" class="btn btn-primary">Submit</button>
</form>