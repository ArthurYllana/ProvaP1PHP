<?php
require_once 'DataBase.php';

$db = new DataBase();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['acao'])) {
    $acao = $_POST['acao'];
    
    switch ($acao) {
        case 'inserir':
            if (empty($_POST['pro_descricao']) || empty($_POST['pro_fabricante']) || 
                empty($_POST['pro_ingredientes']) || empty($_POST['pro_orientacoes'])) {
                $mensagem = "<p style='color:red;'>Erro: Todos os campos são obrigatórios para cadastro.</p>";
                break;
            }
            
            if ($db->createProduct($_POST['pro_descricao'], $_POST['pro_fabricante'], 
                                $_POST['pro_ingredientes'], $_POST['pro_orientacoes'])) {
                $mensagem = "<p style='color:green;'>Produto cadastrado com sucesso!</p>";
            } else {
                $mensagem = "<p style='color:red;'>Erro ao cadastrar produto.</p>";
            }
            break;
        
        case 'consultar':
            if (empty($_POST['consulta_pro_cod'])) {
                $mensagem = "<p style='color:red;'>Erro: Informe o código do produto para consulta.</p>";
                break;
            }
            
            $produto = $db->readProduct($_POST['consulta_pro_cod']);
            if ($produto) {
                $resultadoConsulta = "<fieldset><legend>Dados do Produto</legend>";
                $resultadoConsulta .= "<p><strong>Código:</strong> " . htmlspecialchars($produto['pro_cod']) . "</p>";
                $resultadoConsulta .= "<p><strong>Descrição:</strong> " . htmlspecialchars($produto['pro_descricao']) . "</p>";
                $resultadoConsulta .= "<p><strong>Fabricante:</strong> " . htmlspecialchars($produto['pro_fabricante']) . "</p>";
                $resultadoConsulta .= "<p><strong>Ingredientes:</strong> " . htmlspecialchars($produto['pro_ingredientes']) . "</p>";
                $resultadoConsulta .= "<p><strong>Orientações:</strong> " . htmlspecialchars($produto['pro_orientacoes']) . "</p>";
                $resultadoConsulta .= "</fieldset>";
            } else {
                $mensagem = "<p style='color:red;'>Nenhum produto encontrado com este código.</p>";
            }
            break;

        case 'alterar':
            if (empty($_POST['consulta_pro_cod'])) {
                $mensagem = "<p style='color:red;'>Erro: Informe o código do produto para alteração.</p>";
                break;
            }
            if (empty($_POST['pro_descricao']) || empty($_POST['pro_fabricante']) || 
                empty($_POST['pro_ingredientes']) || empty($_POST['pro_orientacoes'])) {
                $mensagem = "<p style='color:red;'>Erro: Todos os campos são obrigatórios para alteração.</p>";
                break;
            }
            
            if ($db->updateProduct($_POST['consulta_pro_cod'], $_POST['pro_descricao'], 
                                $_POST['pro_fabricante'], $_POST['pro_ingredientes'], $_POST['pro_orientacoes'])) {
                $mensagem = "<p style='color:green;'>Produto alterado com sucesso!</p>";
            } else {
                $mensagem = "<p style='color:red;'>Erro ao alterar produto.</p>";
            }
            break;

        case 'apagar':
            if (empty($_POST['consulta_pro_cod'])) {
                $mensagem = "<p style='color:red;'>Erro: Informe o código do produto para exclusão.</p>";
                break;
            }
            
            if ($db->deleteProduct($_POST['consulta_pro_cod'])) {
                $mensagem = "<p style='color:green;'>Produto excluído com sucesso.</p>";
            } else {
                $mensagem = "<p style='color:red;'>Erro ao excluir produto.</p>";
            }
            break;

        default:
            $mensagem = "<p style='color:red;'>Ação inválida.</p>";
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produtos</title>
    <style>
        fieldset {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ccc;
        }
        legend {
            font-weight: bold;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input[type="text"] {
            width: 300px;
            padding: 5px;
        }
        button {
            margin-top: 10px;
            padding: 5px 15px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <!-- Formulário de Cadastro -->
        <fieldset>
            <legend>Cadastrar Novo Produto</legend>
            <label for="pro_descricao">Descrição:</label>
            <input type="text" id="pro_descricao" name="pro_descricao">
            
            <label for="pro_fabricante">Fabricante:</label>
            <input type="text" id="pro_fabricante" name="pro_fabricante">
            
            <label for="pro_ingredientes">Ingredientes:</label>
            <input type="text" id="pro_ingredientes" name="pro_ingredientes">
            
            <label for="pro_orientacoes">Orientações:</label>
            <input type="text" id="pro_orientacoes" name="pro_orientacoes">
            
            <button type="submit" name="acao" value="inserir">Cadastrar</button>
        </fieldset>

        <!-- Formulário de Consulta/Alteração/Exclusão -->
        <fieldset>
            <legend>Consultar/Alterar/Excluir</legend>
            <label for="consulta_pro_cod">Código do Produto:</label>
            <input type="text" id="consulta_pro_cod" name="consulta_pro_cod">
            
            <div style="margin-top: 15px;">
                <button type="submit" name="acao" value="consultar">Consultar</button>
                <button type="submit" name="acao" value="alterar">Alterar</button>
                <button type="submit" name="acao" value="apagar">Excluir</button>
            </div>
        </fieldset>
    </form>

    <?php
    if (isset($mensagem)) {
        echo $mensagem;
    }
    
    if (isset($resultadoConsulta)) {
        echo $resultadoConsulta;
    }
    ?>
</body>
</html>