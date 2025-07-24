<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Signup | Trwnio</title>
  <link rel="stylesheet" href="{{ asset('css/user_information.css') }}" />
</head>
<body>
  <div class="form-container">
    <form id="multi-step-form" method="POST" action="{{ url('/user-information') }}">
        @csrf
      <!-- Step 1: Personal Info -->
      <div class="form-step active">
        <h2>Personal Information</h2>
        <label>Age
          <input type="number" name="age" required />
        </label>
        <label>Gender
          <select name="gender" required>
            <option value="">Select</option>
            <option>Male</option>
            <option>Female</option>
            <option>Other</option>
          </select>
        </label>
        <div class="form-navigation">
          <button type="button" class="next-btn">Next</button>
        </div>
      </div>
      <!-- Step 2: Education & Expertise -->
      <div class="form-step">
        <h2>Education & Expertise</h2>
        <label>What are you currently studying?
            <select name="studying_in" required>
              <option value="">Select</option>
              <option>B.Tech</option>
              <option>M.Tech</option>
              <option>B.Sc</option>
              <option>M.Sc</option>
              <option>BCA</option>
              <option>MCA</option>
              <option>Diploma</option>
              <option>Other</option>
            </select>
          </label>
          <label>Which field are you an expert in?
            <select name="expert_in" required>
              <option value="">Select</option>
              <option>Software Development</option>
              <option>Artificial Intelligence</option>
              <option>Machine Learning</option>
              <option>Web Development</option>
              <option>Data Science</option>
              <option>Cybersecurity</option>
              <option>Cloud Computing</option>
              <option>Other</option>
            </select>
          </label>
        <div class="form-navigation">
          <button type="button" class="back-btn">Back</button>
          <button type="submit" class="submit-btn">Submit</button>
        </div>
      </div>
    </form>
  </div>

  <script src="{{ asset('js/user_information.js') }}"></script>
</body>
</html>