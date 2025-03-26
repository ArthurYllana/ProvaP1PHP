<?php
    require_once 'DBProdutos.php';
    $bdProdutos = new DBProdutos();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="tela.css" />
    <title>Formulário de Cadastro de Produtos</title>
</head>
<body>
    <h2>Cadastro de Produto</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="prod_descricao">Descrição:</label><br>
        <input type="text" id="prod_descricao" name="prod_descricao" required><br>

        <label for="prod_fabricante">Fabricante:</label><br>
        <input type="text" id="prod_fabricante" name="prod_fabricante"><br>

        <label for="prod_ingredientes">Ingredientes:</label><br>
        <input type="text" id="prod_ingredientes" name="prod_ingredientes"><br>

        <label for="prod_orientacao">Orientação:</label><br>
        <input type="text" id="prod_orientacao" name="prod_orientacao"><br><br>
        
        <button type="submit" name="acao" value="inserir">Enviar</button>
    </form>

    <h2>Consultar, Alterar ou Apagar Produto</h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="prod_cod">Código do Produto:</label><br>
        <input type="text" id="prod_cod" name="prod_cod" required><br>
        
        <button type="submit" name="acao" value="consultar">Consultar</button>
        <button type="submit" name="acao" value="alterar">Alterar</button>
        <button type="submit" name="acao" value="apagar">Apagar</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $acao = $_POST['acao'];
        $prod_cod = $_POST['prod_cod'] ?? null;
        $prod_descricao = $_POST['prod_descricao'] ?? null;
        $prod_fabricante = $_POST['prod_fabricante'] ?? null;
        $prod_ingredientes = $_POST['prod_ingredientes'] ?? null;
        $prod_orientacao = $_POST['prod_orientacao'] ?? null;

        switch ($acao) {
            case 'inserir':
                if ($bdProdutos->create($prod_descricao, $prod_fabricante, $prod_ingredientes, $prod_orientacao)) {
                    echo "<p>Produto inserido com sucesso!</p>";
                } else {
                    echo "<p>Erro ao incluir produto.</p>";
                }
                break;
            
            case 'consultar':
                $produto = $bdProdutos->read($prod_cod);
                if ($produto) {
                    echo "<h2>Dados do Produto</h2>";
                    echo "Código: " . htmlspecialchars($produto['prod_cod']) . "<br>";
                    echo "Descrição: " . htmlspecialchars($produto['prod_descricao']) . "<br>";
                    echo "Fabricante: " . (isset($produto['prod_fabricante']) ? htmlspecialchars($produto['prod_fabricante']) : "Não informado") . "<br>";
                    echo "Ingredientes: " . (isset($produto['prod_ingredientes']) ? htmlspecialchars($produto['prod_ingredientes']) : "Não informado") . "<br>";
                    echo "Orientação: " . (isset($produto['prod_orientacao']) ? htmlspecialchars($produto['prod_orientacao']) : "Não informado") . "<br>";
                } else {
                    echo "<p>Nenhum produto encontrado para o código fornecido.</p>";
                }
                break;

            case 'alterar':
                if ($bdProdutos->update($prod_cod, $prod_descricao, $prod_fabricante, $prod_ingredientes, $prod_orientacao)) {
                    echo "<p>Dados do produto alterados com sucesso!</p>";
                } else {
                    echo "<p>Erro ao alterar os dados do produto.</p>";
                }
                break;

            case 'apagar':
                if ($bdProdutos->delete($prod_cod)) {
                    echo "<p>Produto apagado com sucesso.</p>";
                } else {
                    echo "<p>Erro ao apagar produto. Código inexistente.</p>";
                }
                break;

            default:
                echo "<p>Ação inválida.</p>";
                break;
        }
    }
    ?>
</body>
</html>
