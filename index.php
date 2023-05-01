<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Poetry Bot</title>
  <!-- Load Dependencies -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/ace-builds@1.18.0/src-min-noconflict/ace.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/ace-builds@1.18.0/css/ace.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/uikit@3.16.15/dist/js/uikit.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/uikit@3.16.15/dist/css/uikit.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/typewriter-effect@2.17.0/dist/core.js"></script>
  <style>
    body {
      font-family: 'Open Sans', sans-serif;
    }

    .form-container {
      max-width: 800px;
      margin: 0 auto;
    }

    label {
      font-weight: bold;
      display: block;
      margin-bottom: 5px;
    }

    .uk-form-controls {
      margin-top: 10px;
    }

    #submit {
      margin-top: 10px;
    }

    #output {
      margin-top: 10px;
      white-space: pre-wrap;
    }

    .poem-card {
      border: 1px solid #ccc;
      padding: 20px;
      border-radius: 4px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    textarea {
      font-size: 16px;
      padding: 10px;
    }
  </style>
</head>

<body>
  <section class="uk-section">
    <div class="uk-container form-container">
      <h1 class="uk-text-center">Poetry Creator</h1>
      <p class="uk-text-center">Enter your ideas below, and the Poetry Creator will generate a poem for you. Use separate lines for different ideas or topics.</p>
      <form class="uk-form-stacked">
        <div class="uk-margin">
          <label class="uk-form-label" for="code">Enter your ideas:</label>
          <div class="uk-form-controls">
            <textarea class="uk-textarea" rows="5" id="code" placeholder="Enter your ideas here..."></textarea>
          </div>
        </div>
        <div class="uk-margin">
          <button class="uk-button uk-button-primary" id="submit" type="button">Submit</button>
        </div>
        <div class="uk-margin">
                  <progress id="progress" class="uk-progress" value="0" max="100" hidden></progress>
        <div class="uk-margin">
          <div id="output" class="poem-card"></div>
        </div>
      </form>
    </div>
  </section>
  <script>
    $(document).ready(function() {
      $("#submit").click(function() {
        var code = $("#code").val();
        var submitButton = $(this);
        submitButton.prop("disabled", true);
        $("#progress").attr("hidden", false);

        // Initialize TypewriterJS
        var typewriter = new Typewriter('#output', {
          loop: false,
          delay: 50,
          autoStart: false,
          cursor: '|',
          wrapperClassName: 'Typewriter__wrapper',
          cursorClassName: 'Typewriter__cursor'
        });

        // Display loading message
        typewriter.deleteAll()
          .typeString('Loading...')
          .pauseFor(1000)
          .start();

        $.ajax({
          type: "POST",
          url: "getcode.php",
          contentType: "application/json; charset=utf-8",
          data: JSON.stringify({
            code: code
          }),
          dataType: "json",
          success: function(data) {
            if (data.error) {
              console.log("Error:", data.error);
            } else {
              console.log("Success:", data.response);

              // Clear the output element before starting TypewriterJS
              typewriter.deleteAll();

              // Type the output received from the API or processing function
              typewriter.typeString(data.response)
                .callFunction(() => {
                  submitButton.prop("disabled", false);
                  $("#progress").attr("hidden", true);
                })
                .start();
            }
          },
          error: function(xhr, status, error) {
            console.log("Error:", xhr, status, error);
            typewriter.deleteAll()
              .typeString('An error occurred. Please try again.')
              .callFunction(() => {
                submitButton.prop("disabled", false);
                $("#progress").attr("hidden", true);
              })
              .start();
          }
        });
      });
    });
  </script>
</body>

</html>
