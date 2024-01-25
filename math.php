<?php
function transform_formula($formula, $x_value)
{
    // Replace x with ($x) and ^ with **
    $transformed_formula = str_replace('x', "($x_value)", $formula);
    $transformed_formula = str_replace('^', '**', $transformed_formula);
    return $transformed_formula;
}

function evaluate_formula($formula, $x_value)
{
    $transformed_formula = transform_formula($formula, $x_value);
    $result = eval("return $transformed_formula;");
    return $result;
}

function binary_search($formula, $target, $lower_bound, $upper_bound)
{
    $iterations = 0;
    $table = [];

    while (($upper_bound - $lower_bound) > $target) {
        $midpoint = ($lower_bound + $upper_bound) / 2;
        $fa = evaluate_formula($formula, $lower_bound);
        $fc = evaluate_formula($formula, $midpoint);

        $table[] = [
            'Iteration' => $iterations + 1,
            'A' => $lower_bound,
            'B' => $upper_bound,
            'C' => $midpoint,
            'F(A)' => $fa,
            'F(C)' => $fc,
            '(B)-(A)' => $upper_bound - $lower_bound,
        ];

        if (($fa * $fc) > 0) {
            $lower_bound = $midpoint;
        } else {
            $upper_bound = $midpoint;
        }

        $iterations++;
    }

    $result = ($lower_bound + $upper_bound) / 2;
    $fa = evaluate_formula($formula, $lower_bound);
    $fc = evaluate_formula($formula, $result);

    $table[] = [
        'Iteration' => $iterations + 1,
        'A' => $lower_bound,
        'B' => $upper_bound,
        'C' => $result,
        'F(A)' => $fa,
        'F(C)' => $fc,
        '(B)-(A)' => $upper_bound - $lower_bound,
    ];

    return ['result' => $result, 'table' => $table];
}

// Example usage
$original_formula = $_POST['formula'];
$target_value = (float) $_POST['e'];
$lower_bound = (float) $_POST['a'];
$upper_bound = (float) $_POST['b'];

$result_data = binary_search($original_formula, $target_value, $lower_bound, $upper_bound);
$result = $result_data['result'];
$table = $result_data['table'];
?>
<link rel="stylesheet" href="index.css">
<table>
    <thead>
        <tr>
            <th>I</th>
            <th>A</th>
            <th>B</th>
            <th>C</th>
            <th>F(A)</th>
            <th>F(C)</th>
            <th>(B)-(A)</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($table as $row): ?>
        <tr>
            <td><?= $row['Iteration'] ?></td>
            <td><?= number_format($row['A'], 4) ?></td>
            <td><?= number_format($row['B'], 4) ?></td>
            <td><?= number_format($row['C'], 4) ?></td>
            <td><?= number_format($row['F(A)'], 4) ?></td>
            <td><?= number_format($row['F(C)'], 4) ?></td>
            <td><?= number_format($row['(B)-(A)'], 4) ?></td>
        </tr>
    <?php endforeach; ?>
        <tr>
            <td colspan="2">Формула: <?=$original_formula?></td>
            <td>A: <?=$lower_bound?></td>
            <td>B: <?=$upper_bound?></td>
            <td>E: <?=$target_value?></td>
            <td colspan="2">Найденый корень: <?=$result?></td>
        </tr>
    </tbody>
</table>
