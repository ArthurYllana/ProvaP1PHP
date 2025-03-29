<?php
require_once 'DataBase.php';
require_once 'BDProdutos.php';

$database = new DataBase();
$dbProdutos = new DBProdutos();

$mensagem = '';
$resultadoConsulta = '';
$listaProdutos = '';

//Armazem mensagem de status, resultados de consultas ou lista de produtos 

// Ação padrão (listar todos)
$acao = isset($_POST['acao']) ? $_POST['acao'] : 'listar'; //Verificando se a variável foi enviada para o formulario e não é null

switch ($acao) {  //Executando a ação se a opção inserir for executada 
    case 'inserir':
        if (empty($_POST['pro_descricao']) || empty($_POST['pro_fabricante']) || 
            empty($_POST['pro_ingredientes']) || empty($_POST['pro_orientacoes'])) {
            $mensagem = "<p style='color:red;'>Erro: Todos os campos são obrigatórios para cadastro.</p>"; // Verificando se os campos estão vazios
            break;
        }
        
        try {
            if ($dbProdutos->create($_POST['pro_descricao'], $_POST['pro_fabricante'], 
                                $_POST['pro_ingredientes'], $_POST['pro_orientacoes'])) {
                $mensagem = "<p style='color:green;'>Produto cadastrado com sucesso!</p>"; //Usado para inserir um novo produto 
            } else {
                $mensagem = "<p style='color:red;'>Erro ao cadastrar produto.</p>";
            }
        } catch (Exception $e) {
            $mensagem = "<p style='color:red;'>Erro: " . $e->getMessage() . "</p>";
        }
        break;
    
    case 'consultar':
        if (empty($_POST['consulta_pro_cod'])) {
            $mensagem = "<p style='color:red;'>Erro: Informe o código do produto para consulta.</p>";
            break; //Valida se o código foi informado 
        }
        
        try { //Fazendo a busca do produto no banco de dados
            $produto = $dbProdutos->recoveryById($_POST['consulta_pro_cod']);
            if ($produto) {
                $resultadoConsulta = "<fieldset><legend>Dados do Produto</legend>";//Agrupando dados do produtos
                $resultadoConsulta .= "<form method='post'>";
                $resultadoConsulta .= "<input type='hidden' name='consulta_pro_cod' value='" . $produto['pro_cod'] . "'>"; // armazena o código do produto para alteração
                $resultadoConsulta .= "<p><strong>Código:</strong> " . htmlspecialchars($produto['pro_cod']) . "</p>";
                $resultadoConsulta .= "<label><strong>Descrição:</strong><br>";
                $resultadoConsulta .= "<input type='text' name='pro_descricao' value='" . htmlspecialchars($produto['pro_descricao']) . "' style='width:100%;'></label><br>";
                $resultadoConsulta .= "<label><strong>Fabricante:</strong><br>";
                $resultadoConsulta .= "<input type='text' name='pro_fabricante' value='" . htmlspecialchars($produto['pro_fabricante']) . "' style='width:100%;'></label><br>";
                $resultadoConsulta .= "<label><strong>Ingredientes:</strong><br>";
                $resultadoConsulta .= "<input type='text' name='pro_ingredientes' value='" . htmlspecialchars($produto['pro_ingredientes']) . "' style='width:100%;'></label><br>";
                $resultadoConsulta .= "<label><strong>Orientações:</strong><br>";
                $resultadoConsulta .= "<input type='text' name='pro_orientacoes' value='" . htmlspecialchars($produto['pro_orientacoes']) . "' style='width:100%;'></label><br>";
                $resultadoConsulta .= "<button type='submit' name='acao' value='alterar'>Salvar Alterações</button>";
                $resultadoConsulta .= "</form>";
                $resultadoConsulta .= "</fieldset>";
            } else {
                $mensagem = "<p style='color:red;'>Nenhum produto encontrado com este código.</p>";
            }
        } catch (Exception $e) {
            $mensagem = "<p style='color:red;'>Erro: " . $e->getMessage() . "</p>";
        }
        break;

        case 'buscar':
            if (empty($_POST['busca_descricao'])) { //Verificando se o campo está vazio
                $mensagem = "<p style='color:red;'>Erro: Informe um termo para busca.</p>";
                break;
            }
            
            try {
                $produtos = $dbProdutos->searchByDescription($_POST['busca_descricao']); //Realiza a busca pelo banco de dados
                
                if ($produtos) { //Monta a tabela para exibir os resultados 
                    $listaProdutos = "<fieldset><legend>Resultados da Busca por: " . htmlspecialchars($_POST['busca_descricao']) . "</legend>";
                    $listaProdutos .= "<table border='1' style='width:100%; border-collapse: collapse;'>";
                    $listaProdutos .= "<tr><th>Código</th><th>Descrição</th><th>Fabricante</th><th>Ingredientes</th><th>Orientações</th><th>Ações</th></tr>"; 
                    
                    foreach ($produtos as $produto) { //Percerro o arry dos produtos e 
                        $listaProdutos .= "<tr>";
                        $listaProdutos .= "<td>" . htmlspecialchars($produto['pro_cod']) . "</td>";
                        $listaProdutos .= "<td>" . htmlspecialchars($produto['pro_descricao']) . "</td>";
                        $listaProdutos .= "<td>" . htmlspecialchars($produto['pro_fabricante']) . "</td>";
                        $listaProdutos .= "<td>" . htmlspecialchars($produto['pro_ingredientes']) . "</td>";
                        $listaProdutos .= "<td>" . htmlspecialchars($produto['pro_orientacoes']) . "</td>";
                        $listaProdutos .= "<td>
                            <form method='post' style='display:inline;'> 
                                <input type='hidden' name='consulta_pro_cod' value='" . $produto['pro_cod'] . "'>
                                <button type='submit' name='acao' value='consultar'>Consultar</button>
                            </form>
                            <form method='post' style='display:inline;'>
                                <input type='hidden' name='consulta_pro_cod' value='" . $produto['pro_cod'] . "'>
                                <button type='submit' name='acao' value='apagar' onclick='return confirm(\"Tem certeza que deseja excluir?\")'>Excluir</button>
                            </form>
                        </td>";
                        $listaProdutos .= "</tr>";
                    }
                    
                    $listaProdutos .= "</table>";
                    $listaProdutos .= "</fieldset>";
                } else {
                    $mensagem = "<p style='color:blue;'>Nenhum produto encontrado com o termo: " . htmlspecialchars($_POST['busca_descricao']) . "</p>";
                }
            } catch (Exception $e) {
                $mensagem = "<p style='color:red;'>Erro na busca: " . $e->getMessage() . "</p>";
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
        
        try { //Passa os valores recebidos
            if ($dbProdutos->update($_POST['consulta_pro_cod'], $_POST['pro_descricao'], 
                                $_POST['pro_fabricante'], $_POST['pro_ingredientes'], $_POST['pro_orientacoes'])) {
                $mensagem = "<p style='color:green;'>Produto alterado com sucesso!</p>";
            } else {
                $mensagem = "<p style='color:red;'>Erro ao alterar produto.</p>";
            }
        } catch (Exception $e) {
            $mensagem = "<p style='color:red;'>Erro: " . $e->getMessage() . "</p>";
        }
        break;

    case 'apagar':
        if (empty($_POST['consulta_pro_cod'])) {
            $mensagem = "<p style='color:red;'>Erro: Informe o código do produto para exclusão.</p>";
            break;
        }
        
        try {
            if ($dbProdutos->delete($_POST['consulta_pro_cod'])) {
                $mensagem = "<p style='color:green;'>Produto excluído com sucesso.</p>";
            } else {
                $mensagem = "<p style='color:red;'>Erro ao excluir produto.</p>";
            }
        } catch (Exception $e) {
            $mensagem = "<p style='color:red;'>Erro: " . $e->getMessage() . "</p>";
        }
        break;

    case 'listar':
    default: //Se a aplicação não reconhecer a ação enviada, ela exibirá a lista de produtos por padrão.
        try {
            $produtos = $dbProdutos->listAll(); //Busca os produtos do banco de dados
            if ($produtos) {
                $listaProdutos = "<fieldset><legend>Lista de Produtos</legend>";
                $listaProdutos .= "<table border='1' style='width:100%; border-collapse: collapse;'>";
                $listaProdutos .= "<tr><th>Código</th><th>Descrição</th><th>Fabricante</th><th>Ingredientes</th><th>Orientações</th><th>Ações</th></tr>";
                
                foreach ($produtos as $produto) {
                    $listaProdutos .= "<tr>";
                    $listaProdutos .= "<td>" . htmlspecialchars($produto['pro_cod']) . "</td>";
                    $listaProdutos .= "<td>" . htmlspecialchars($produto['pro_descricao']) . "</td>";
                    $listaProdutos .= "<td>" . htmlspecialchars($produto['pro_fabricante']) . "</td>";
                    $listaProdutos .= "<td>" . htmlspecialchars($produto['pro_ingredientes']) . "</td>";
                    $listaProdutos .= "<td>" . htmlspecialchars($produto['pro_orientacoes']) . "</td>";
                    $listaProdutos .= "<td>
                        <form method='post' style='display:inline;'>
                            <input type='hidden' name='consulta_pro_cod' value='" . $produto['pro_cod'] . "'>
                            <button type='submit' name='acao' value='consultar'>Consultar</button>
                        </form>
                        <form method='post' style='display:inline;'>
                            <input type='hidden' name='consulta_pro_cod' value='" . $produto['pro_cod'] . "'>
                            <button type='submit' name='acao' value='apagar' onclick='return confirm(\"Tem certeza que deseja excluir?\")'>Excluir</button>
                        </form>
                    </td>";
                    $listaProdutos .= "</tr>";
                }
                
                $listaProdutos .= "</table>";
                $listaProdutos .= "</fieldset>";
            } else {
                $listaProdutos = "<p>Nenhum produto cadastrado.</p>";
            }
        } catch (Exception $e) {
            $mensagem = "<p style='color:red;'>Erro ao listar produtos: " . $e->getMessage() . "</p>";
        }
        break;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produtos</title>
    <link rel="stylesheet" type="text/css" href="tela.css"/>

</head>
<body>
    <h1>Sistema de Cadastro de Produtos</h1>
    
    <!-- Formulário de Cadastro -->
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <fieldset>
            <legend>Cadastrar Novo Produto</legend>
            <label for="pro_descricao">Descrição:</label>
            <input type="text" id="pro_descricao" name="pro_descricao" required>
            
            <label for="pro_fabricante">Fabricante:</label>
            <input type="text" id="pro_fabricante" name="pro_fabricante" required>
            
            <label for="pro_ingredientes">Ingredientes:</label>
            <input type="text" id="pro_ingredientes" name="pro_ingredientes" required>
            
            <label for="pro_orientacoes">Orientações:</label>
            <input type="text" id="pro_orientacoes" name="pro_orientacoes" required>
            
            <button type="submit" name="acao" value="inserir">Cadastrar</button>
        </fieldset>
    </form>

    <!-- Formulário de Busca -->
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <fieldset>
            <legend>Buscar Produto</legend>
            <label for="busca_descricao">Descrição (ou parte dela):</label>
            <input type="text" id="busca_descricao" name="busca_descricao">
            
            <button type="submit" name="acao" value="buscar">Buscar</button>
        </fieldset>
    </form>

    <!-- Formulário de Consulta/Alteração/Exclusão -->
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <fieldset>
            <legend>Consultar/Alterar/Excluir</legend>
            <label for="consulta_pro_cod">Código do Produto:</label>
            <input type="text" id="consulta_pro_cod" name="consulta_pro_cod">
            
            <div style="margin-top: 15px;">
                <button type="submit" name="acao" value="consultar">Consultar</button>
                <button type="submit" name="acao" value="listar">Listar Todos</button>
            </div>
        </fieldset>
    </form>

    <?php
    // Exibe mensagens de status
    if (isset($mensagem)) {
        echo $mensagem;
    }
    
    // Exibe resultado de consulta
    if (isset($resultadoConsulta)) {
        echo $resultadoConsulta;
    }
    
    // Exibe lista de produtos
    if (isset($listaProdutos)) {
        echo $listaProdutos;
    }
    ?>
</body>
</html>