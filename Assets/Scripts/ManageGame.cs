using UnityEngine;
using UnityEngine.UI;
using MySql.Data.MySqlClient;
using System.Collections.Generic;

public class ManageGame : MonoBehaviour
{
    public string host;
    public string password;
    public string database;
    public string username;
    public Image fromageImage;  // L'image où sera affiché le fromage
    public Button[] buttons;    // Les boutons pour les réponses
    public Sprite defaultSprite; // Sprite par défaut si l'image n'est pas trouvée

    private MySqlConnection con;
    private string correctFromageName;
    private List<Fromage> fromages = new List<Fromage>();

    // Structure pour les informations de fromage
    public struct Fromage
    {
        public int id;
        public string nom;
        public int difficulte;
    }

    void Start()
    {
        // Connexion à la base de données et récupération des fromages
        ConnectBDD();
        LoadFromages();
        ShowRandomFromage();
    }

    // Connexion à la base de données
    void ConnectBDD()
    {
        string constr = "Server=" + host + ";DATABASE=" + database + ";User ID=" + username + ";Password=" + password + ";Pooling=true;Charset=utf8;";
        con = new MySqlConnection(constr);
        try
        {
            con.Open();
        }
        catch (MySqlException ex)
        {
            Debug.LogError("Connexion à la base de données échouée: " + ex.Message);
        }
    }

    // Charger tous les fromages de difficulté 1
    void LoadFromages()
    {
        string query = "SELECT id, nom, difficulte FROM Fromage WHERE difficulte = 1";
        MySqlCommand cmd = new MySqlCommand(query, con);
        MySqlDataReader reader = cmd.ExecuteReader();

        while (reader.Read())
        {
            Fromage f = new Fromage();
            f.id = reader.GetInt32("id");
            f.nom = reader.GetString("nom");
            f.difficulte = reader.GetInt32("difficulte");
            fromages.Add(f);
        }

        reader.Close();
    }

    // Afficher un fromage aléatoire avec 3 options de réponse
    void ShowRandomFromage()
    {
        if (fromages.Count == 0) return;

        // Sélectionner un fromage aléatoire
        Fromage selectedFromage = fromages[Random.Range(0, fromages.Count)];
        correctFromageName = selectedFromage.nom;

        // Charger et afficher l'image du fromage
        LoadFromageImage(correctFromageName);

        // Remplir les boutons avec un nom correct et deux noms incorrects
        List<string> buttonOptions = new List<string> { correctFromageName };

        // Ajouter deux autres noms aléatoires
        while (buttonOptions.Count < 3)
        {
            Fromage randomFromage = fromages[Random.Range(0, fromages.Count)];
            if (!buttonOptions.Contains(randomFromage.nom))
            {
                buttonOptions.Add(randomFromage.nom);
            }
        }

        // Mélanger les options
        ShuffleList(buttonOptions);

        // Assignation des noms aux boutons
        for (int i = 0; i < buttons.Length; i++)
        {
            string option = buttonOptions[i];
            buttons[i].GetComponentInChildren<Text>().text = option;
            buttons[i].onClick.RemoveAllListeners();
            buttons[i].onClick.AddListener(() => CheckAnswer(option));
        }
    }

    // Vérifier si l'utilisateur a sélectionné la bonne réponse
    void CheckAnswer(string selectedName)
    {
        if (selectedName == correctFromageName)
        {
            Debug.Log("Bonne réponse !");
            ShowRandomFromage(); // Afficher un nouveau fromage
        }
        else
        {
            Debug.Log("Mauvaise réponse, réessaye.");
        }
    }

    // Charger l'image du fromage à partir du nom
    void LoadFromageImage(string fromageName)
    {
        // Associer le nom du fromage avec l'image (les images doivent être dans Resources/Fromages)
        Sprite fromageSprite = Resources.Load<Sprite>("Images/Fromages" + fromageName);

        if (fromageSprite != null)
        {
            fromageImage.sprite = fromageSprite;
        }
        else
        {
            fromageImage.sprite = defaultSprite; // Afficher une image par défaut si aucune n'est trouvée
        }
    }

    // Fonction pour mélanger une liste
    void ShuffleList(List<string> list)
    {
        for (int i = 0; i < list.Count; i++)
        {
            string temp = list[i];
            int randomIndex = Random.Range(i, list.Count);
            list[i] = list[randomIndex];
            list[randomIndex] = temp;
        }
    }

    void OnApplicationQuit()
    {
        if (con != null && con.State == System.Data.ConnectionState.Open)
        {
            con.Close();
        }
    }
}
