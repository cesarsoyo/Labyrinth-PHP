<?php
session_start();

$boards = [
  [
    [0, 0, 1, 0, 1, 0, 1],
    [0, 0, 1, 0, 0, 0, 1],
    [1, 0, 0, 0, 1, 1, 0],
    [1, 0, 1, 0, 0, 0, 2],
    [0, 0, 1, 0, 1, 1, 0]
  ],
  [
    [1, 0, 0, 1, 1, 0, 1],
    [1, 0, 0, 1, 0, 0, 1],
    [0, 0, 0, 1, 0, 0, 0],
    [1, 1, 0, 0, 0, 1, 0],
    [0, 0, 0, 1, 0, 0, 2]
  ], [
    [0, 0, 1, 0, 0, 0, 0],
    [1, 0, 1, 0, 0, 0, 1],
    [0, 0, 0, 0, 1, 0, 0],
    [1, 0, 1, 0, 1, 0, 2],
    [0, 0, 1, 0, 1, 1, 0]
  ], [
    [1, 0, 1, 0, 1, 0, 0],
    [0, 0, 1, 0, 1, 2, 0],
    [0, 0, 0, 0, 1, 1, 0],
    [1, 0, 1, 0, 1, 0, 0],
    [0, 0, 0, 0, 0, 0, 1]
  ], [
    [0, 0, 1, 1, 0, 0, 0],
    [0, 1, 1, 0, 0, 0, 0],
    [0, 0, 1, 0, 1, 1, 0],
    [0, 0, 0, 0, 1, 2, 0],
    [0, 0, 1, 0, 1, 1, 0]
  ]
];

function fogMap($board)
{
  $playerPos = $_SESSION["player"];
  $foggedBoard = $board;

  for ($i = 0; $i < count($board); $i++) {
    for ($j = 0; $j < count($board[$i]); $j++) {
      if (
        abs($i - $playerPos[0]) + abs($j - $playerPos[1]) > 1
      ) {
        $foggedBoard[$i][$j] = 0;
      }
    }
  }

  return $foggedBoard;
}
function checkWin()
{
  $playerPos = $_SESSION["player"];
  $board = $_SESSION["board"];

  if ($board[$playerPos[0]][$playerPos[1]] === 2) {
    return true;
  }

  return false;
}

if (isset($_POST["reset"])) {
  session_destroy();
  header("refresh:0");
} elseif (isset($_POST["direction"])) {
  unset($_SESSION["error"]);
  $playerPos = $_SESSION["player"];
  $board = $_SESSION["board"];
  switch ($_POST["direction"]) {
    case "top":
      if ($playerPos[0] > 0 && $board[$playerPos[0] - 1][$playerPos[1]] !== 1) {
        $_SESSION["player"] = [$playerPos[0] - 1, $playerPos[1]];
      }
      break;

    case "bottom":
      if ($playerPos[0] < count($board) - 1 && $board[$playerPos[0] + 1][$playerPos[1]] !== 1) {
        $_SESSION["player"] = [$playerPos[0] + 1, $playerPos[1]];
      }
      break;

    case "left":
      if ($playerPos[1] > 0 && $board[$playerPos[0]][$playerPos[1] - 1] !== 1) {
        $_SESSION["player"] = [$playerPos[0], $playerPos[1] - 1];
      }
      break;

    case "right":
      if ($playerPos[1] < count($board[$playerPos[0]]) - 1 && $board[$playerPos[0]][$playerPos[1] + 1] !== 1) {
        $_SESSION["player"] = [$playerPos[0], $playerPos[1] + 1];
      }
      break;
  }

  if (checkWin()) {
    $_SESSION["win"] = true;
  }
} else {
  $_SESSION["player"] = [0, 1];
  $boardIndex = rand(0, count($boards) - 1);
  $_SESSION["board"] = $boards[$boardIndex];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
    h1 {
      text-align: center;
      font-size: 24pt;
      font-family: sans-serif;
      font-weight: 800;
      margin: 10px;
    }

    #container {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    #boardContainer {
      border: 5px solid #000;
      box-shadow: 5px 5px 10px #888888;
      padding: 10px;
      margin-bottom: 20px;
      background-color: #ddd;
    }

    .line {
      display: flex;
    }

    .cell {
      width: 50px;
      height: 50px;
      margin: 2px;
      position: relative;
    }

    .cell::before {
      content: '';
      position: absolute;
      top: -2px;
      left: -2px;
      right: -2px;
      bottom: -2px;
      border: 2px solid #000;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }

    .cell2 {
      background-color: #c0c0c0;
    }

    .cell3 {
      background-color: #000;
    }

    .cell1 {
      background-color: #ff0000;
    }

    #resetButton {
      display: <?php echo isset($_SESSION["win"]) ? "block" : "none"; ?>;
      display: flex;
      background-color: #4caf50;
      color: white;
      border: 2px solid #4caf50;
      padding: 10px 20px;
      text-align: center;
      text-decoration: none;
      font-size: 16px;
      margin: 4px 2px;
      cursor: pointer;
      border-radius: 5px;
      box-shadow: 5px 5px 10px #888888;
    }

    form {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 10px 0;
    }

    button {
      background-color: #008CBA;
      border: none;
      color: white;
      padding: 15px 32px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      margin: 4px 2px;
      cursor: pointer;
      border-radius: 5px;
      box-shadow: 5px 5px 10px #888888;
    }
  </style>
</head>

<body>
  <section>
    <h1>Labyrinthe</h1>
    <div id="container">
      <div id="boardCotainer">
        <?php
        foreach (fogMap($_SESSION["board"]) as $i => $line) {
          echo "<div class='line'>";
          foreach ($line as $j => $cell) {
            if ($i === $_SESSION["player"][0] && $j === $_SESSION["player"][1]) {
              echo "<div class='cell cell3'></div>";
            } else {
              echo "<div class='cell cell$cell'></div>";
            }
          }
          echo "</div>";
        }        
        ?>
      </div>
      <form method="post">
        <?php if (isset($_SESSION["win"])) : ?>
          <button id="resetButton" type="submit" name="reset">Recommencer</button>
        <?php endif; ?>
      </form>
    </div>
    <form method="post">
      <button type="submit" name="direction" value="top">Top</button>
      <button type="submit" name="direction" value="bottom">Bottom</button>
      <button type="submit" name="direction" value="left">Left</button>
      <button type="submit" name="direction" value="right">Right</button>
    </form>
  </section>
</body>

</html>
