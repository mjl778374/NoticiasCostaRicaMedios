function ReemplazarTodo(unString, CaracteresXReemplazar, CaracteresReemplazo)
{
    while (unString.indexOf(CaracteresXReemplazar) >= 0)
        unString = unString.replace(CaracteresXReemplazar, CaracteresReemplazo);

    return unString;
} // function ReemplazarTodo(unString, CaracteresXReemplazar, CaracteresReemplazo)

function AbrirURL(URLCarpetaServidor, URLRelativa)
{
    m_sHref = URLCarpetaServidor + "/" + URLRelativa;
    AbrirURLAbsoluta(m_sHref);
} // function AbrirURL(URLCarpetaServidor, URLRelativa)

function AbrirURLAbsoluta(URLAbsoluta)
{
    m_sHref = URLAbsoluta;
    window.open(m_sHref, '_new');
} // function AbrirURLAbsoluta(URLAbsoluta)
