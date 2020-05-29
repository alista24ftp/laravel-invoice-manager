@extends('layouts.app')
@section('title', 'User Profile')

@section('content')
  @include('shared._error')
  <form action="{{route('users.update', $user->id)}}" method="POST" enctype="multipart/form-data">
    @csrf
    {{method_field('PUT')}}
    <div class="card">
      <h5 class="card-header">User Profile</h5>
      <div class="card-body">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" class="form-control" id="username" name="username" value="{{old('username', $user->username)}}" />
        </div>
        <div class="form-group">
          <label for="firstname">First Name</label>
          <input type="text" class="form-control" id="firstname" name="firstname"
            value="{{old('firstname', $user->firstname)}}" />
        </div>
        <div class="form-group">
          <label for="lastname">Last Name</label>
          <input type="text" class="form-control" id="lastname" name="lastname"
            value="{{old('lastname', $user->lastname)}}" />
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="text" class="form-control" id="email" name="email" value="{{old('email', $user->email)}}" />
        </div>
        <div class="form-row">
          <div class="form-group col-3">
            <label for="profile_pic">Profile Picture</label>
            <input type="file" class="form-control-file" id="profile_pic" name="profile_pic" accept=".jpg,.jpeg,.png,.gif" />
          </div>
          <div id="profile_pic_display" class="col-9">
            @if($user->profile_pic)
              <img src="{{$user->profile_pic}}" width="200" height="200" id="profile_img" />
            @endif
            <p id="display_msg"></p>
          </div>
        </div>
      </div>
      <div class="card-footer text-center">
        <button type="submit" class="btn btn-primary">
          Save Changes
        </button>
      </div>
    </div>
  </form>
@endsection

@section('customjs')
  <script>
    (function(){
      const profilePicSelector = document.getElementById('profile_pic');
      let profileImg = document.getElementById('profile_img');
      const profilePicDisplay = document.getElementById('profile_pic_display');
      const displayMsg = document.getElementById('display_msg');
      if(!profileImg){
        profileImg = new Image(200, 200);
        profileImg.id = 'profile_img';
      }
      profilePicSelector.addEventListener('change', function(event){
        let profilePicFile = event.target.files[0];
        profilePicDisplay.insertBefore(profileImg, displayMsg);
        profileImg.src = '';
        if(!profilePicFile.type){
          displayMsg.textContent = 'Error: This browser cannot display chosen file';
          return;
        }
        if(!profilePicFile.type.match('image.*')){
          displayMsg.textContent = 'Error: Chosen file type is not an image';
          return;
        }

        const fileReader = new FileReader();
        fileReader.addEventListener('load', function(event){
          profileImg.src = event.target.result;
        });
        fileReader.addEventListener('progress', function(event){
          if(event.loaded && event.total){
            const percent = (event.loaded / event.total) * 100;
            displayMsg.textContent = "Image loaded: " + Math.round(percent) + '%';
          }
        });
        fileReader.readAsDataURL(profilePicFile);
      });
    })();
  </script>
@endsection
