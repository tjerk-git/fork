<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Debug Scenarios</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }

    h2 {
      color: #2c3e50;
    }

    p,
    li {
      color: #34495e;
    }

    ul {
      list-style-type: none;
      padding: 0;
    }

    li {
      margin-bottom: 10px;
    }

    .scenario,
    .result,
    .line,
    .component,
    .option {
      margin-bottom: 20px;
      padding: 10px;
      border: 1px solid #ecf0f1;
      border-radius: 5px;
      background-color: #f9f9f9;
    }

    .scenario {
      background-color: #e8f8f5;
    }

    .result {
      background-color: #fef9e7;
    }

    .line {
      background-color: #f9ebea;
    }

    .component {
      background-color: #eaf2f8;
    }

    .option {
      background-color: #f4ecf7;
    }

  </style>
</head>
<body>
  @foreach($scenario->results as $result)
  <div class="result">
    <h2>Result</h2>
    <p>{{ $result->body }}</p>
    @foreach($result->lines as $line)
    <div class="line">
      <h2>For step {{ $line->step->id }}</h2>
      <p>{{ $line->value }}</p>
    </div>
    @endforeach
  </div>
  @endforeach
</body>
</html>
