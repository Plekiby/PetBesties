using System.Collections;
using System;
using System.Collections.Generic;
using UnityEngine;
using System.IO;
using UnityEngine.UI;
using MySql.Data.MySqlClient;
using UnityEngine.Networking;  


public class ManageDB : MonoBehaviour
{
    public string host;
    public string password;
    public string database;
    public string username;
    public InputField IfLogin;
    public InputField IfPassword;
    public Text txtTop;
    public Text txtQues;
    public GameObject canvalogin;
    public GameObject canvadiff;
    public GameObject JeuNFini;
    public GameObject JeuFini;
    public Text txtRes;
    private string user, mdp;
    private int score = 0;
    private int vie = 3;
    private string correctFromageName;
    private int userId;
    private List<Fromage> fromages = new List<Fromage>();
    MySqlConnection con;
    public Image fromageImage;  // L'image où sera affiché le fromage
    public Button[] buttons;    // Les boutons pour les réponses
    public Sprite defaultSprite; // Sprite par défaut si l'image n'est pas trouvée
    public Image[] viefromage;

    // Structure pour les informations de fromage
    public struct Fromage
    {
        public int id;
        public string nom;
        public int difficulte;
    }

    public void Jeu()
    {
        // Connexion à la base de données et récupération des fromages
        ConnectBDD();
        LoadFromages();
        ShowRandomFromage();
    }

    // Charger tous les fromages de difficulté 1
    void LoadFromages()
    {
        string query = "SELECT id, nom, difficulte FROM Fromage WHERE difficulte IN (1, 2)";
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

    void ShowRandomFromage()
    {
        if (fromages.Count == 0)
        {
            Debug.LogError("La liste des fromages est vide. Assure-toi que les fromages ont été chargés correctement.");
            return;
        }

        if (fromageImage == null)
        {
            Debug.LogError("Le champ fromageImage n'est pas assigné dans l'Inspector.");
            return;
        }

        if (buttons == null || buttons.Length == 0)
        {
            Debug.LogError("Le tableau de boutons est nul ou vide. Assure-toi que les boutons sont assignés correctement.");
            return;
        }

        // Sélectionner un fromage aléatoire
        Fromage selectedFromage = fromages[UnityEngine.Random.Range(0, fromages.Count)];
        correctFromageName = selectedFromage.nom;

        // Charger et afficher l'image du fromage
        LoadFromageImage(correctFromageName);

        // Afficher le numéro de la question (le num de la question est le score actuel +1)
        txtQues.text = "Question " + (score + 1).ToString();

        // Remplir les boutons avec un nom correct et deux noms incorrects
        List<string> buttonOptions = new List<string> { correctFromageName };

        // Ajouter deux autres noms aléatoires
        while (buttonOptions.Count < 3)
        {
            Fromage randomFromage = fromages[UnityEngine.Random.Range(0, fromages.Count)];
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
            if (buttons[i] == null)
            {
                Debug.LogError("Le bouton à l'index " + i + " est nul.");
                continue;
            }

            Text buttonText = buttons[i].GetComponentInChildren<Text>();
            if (buttonText == null)
            {
                Debug.LogError("Le composant Text n'est pas trouvé sur le bouton à l'index " + i + ".");
                continue;
            }

            string option = buttonOptions[i];
            buttonText.text = option;
            buttons[i].onClick.RemoveAllListeners();
            buttons[i].onClick.AddListener(() => CheckAnswer(option));
        }
    }

    void InsertGameRecord(int finalScore)
{
    ConnectBDD();

    // Vérifiez que l'utilisateur est connecté avant d'insérer le score
    if (userId == 0)
    {
        Debug.LogError("Utilisateur non connecté. Impossible d'enregistrer le score.");
        return;
    }

    // Requête pour insérer une nouvelle partie dans la table Game
    string insertGameQuery = "INSERT INTO Game (user_id, score) VALUES (@userId, @score)";
    MySqlCommand insertCmd = new MySqlCommand(insertGameQuery, con);
    insertCmd.Parameters.AddWithValue("@userId", userId);
    insertCmd.Parameters.AddWithValue("@score", finalScore);

    try
    {
        // Exécuter la commande pour insérer l'enregistrement du jeu
        insertCmd.ExecuteNonQuery();
        Debug.Log("Score enregistré avec succès dans la table Game !");
    }
    catch (MySqlException ex)
    {
        Debug.LogError("Erreur lors de l'insertion du score : " + ex.Message);
    }
    finally
    {
        // Fermer la connexion
        insertCmd.Dispose();
        if (con != null && con.State == System.Data.ConnectionState.Open)
        {
            con.Close();
        }
    }
}



    void LoadFromageImage(string fromageName)
    {
        if (fromageImage == null)
        {
            Debug.LogError("Le champ fromageImage n'est pas assigné.");
            return;
        }

        if (defaultSprite == null)
        {
            Debug.LogError("Le sprite par défaut n'est pas assigné.");
            return;
        }

        Sprite fromageSprite = Resources.Load<Sprite>("Images/Fromages/" + fromageName);

        if (fromageSprite != null)
        {
            fromageImage.sprite = fromageSprite;
        }
        else
        {
            fromageImage.sprite = defaultSprite;
        }
    }


    // Vérifier si l'utilisateur a sélectionné la bonne réponse
    void CheckAnswer(string selectedName)
    {
        if (selectedName == correctFromageName)
        {
            Debug.Log("Bonne réponse !");
            score++;
            ShowRandomFromage(); // Afficher un nouveau fromage
        }
        else
        {
            vie--;

            // Désactiver une des images de "vie"
            if (vie >= 0 && vie < viefromage.Length)
            {
                viefromage[vie].gameObject.SetActive(false);
            }

            // Si le joueur n'a plus de vies, vous pouvez gérer la fin du jeu ici
            if (vie <= 0)
            {
                Debug.Log("Game over");
                InsertGameRecord(score);
                viefromage[0].gameObject.SetActive(true);
                viefromage[1].gameObject.SetActive(true);
                viefromage[2].gameObject.SetActive(true);
                vie = 3;
                JeuNFini.SetActive(false);
                JeuFini.SetActive(true);
                txtRes.text = "BRAVO ! Votre score est de " + score;
                score = 0;
            }
        }
    }

    

    // Fonction pour mélanger une liste
    void ShuffleList(List<string> list)
    {
        for (int i = 0; i < list.Count; i++)
        {
            string temp = list[i];
            int randomIndex = UnityEngine.Random.Range(i, list.Count);
            list[i] = list[randomIndex];
            list[randomIndex] = temp;
        }
    }

    // Start is called before the first frame update
    void ConnectBDD()
    {
        Debug.Log("ConnectBDD");

        string constr = "Server=" + host + ";DATABASE=" + database + ";User ID=" + username + ";Password=" + password + ";Pooling=true;Charset=utf8;";
        try
        {
            con = new MySqlConnection(constr);
            Debug.Log("oui");
            con.Open();
        }
        catch { con = null; }
    }

    // Update is called once per frame
    void Update()
    {
        
    }

    void OnApplicationQuit()
    {
        Debug.Log("OnApplicationQuit");

        Debug.Log("Shutdown Connexion");
        if (con != null && con.State.ToString() !="Closed") {
        con.Close();
        }
    }

public void Register()
{
    ConnectBDD();

    // Vérifier si le nom d'utilisateur existe déjà
    string checkUserCommand = "SELECT COUNT(*) FROM User WHERE username = @username";
    MySqlCommand checkCmd = new MySqlCommand(checkUserCommand, con);
    checkCmd.Parameters.AddWithValue("@username", IfLogin.text);

    try
    {
        // Exécuter la requête pour vérifier si l'utilisateur existe déjà
        object result = checkCmd.ExecuteScalar();

        // Gérer les résultats DBNull et convertir en entier
        int userCount = (result != null && result != DBNull.Value) ? Convert.ToInt32(result) : 0;

        if (userCount > 0)
        {
            // Le nom d'utilisateur existe déjà
            Debug.Log("Le nom d'utilisateur existe déjà !");
        }
        else
        {
            // Insérer un nouvel utilisateur
            string insertUserCommand = "INSERT INTO User (username, mdp) VALUES (@username, @password)";
            MySqlCommand insertCmd = new MySqlCommand(insertUserCommand, con);
            insertCmd.Parameters.AddWithValue("@username", IfLogin.text);
            insertCmd.Parameters.AddWithValue("@password", IfPassword.text);

            try
            {
                // Exécuter la requête pour insérer l'utilisateur
                insertCmd.ExecuteNonQuery();
                
                // Récupérer l'ID auto-incrémenté de l'utilisateur
                string getIdCommand = "SELECT LAST_INSERT_ID()";
                MySqlCommand getIdCmd = new MySqlCommand(getIdCommand, con);
                userId = Convert.ToInt32(getIdCmd.ExecuteScalar());  // Stocker l'ID de l'utilisateur dans la variable userId

                Debug.Log("Enregistrement réussi! ID de l'utilisateur: " + userId);

                // Enregistrement réussi, activer le canvas principal et désactiver le canvas de login
                user = IfLogin.text;
                mdp = IfPassword.text;
                canvalogin.SetActive(false);  // Désactive le canvas de login
                canvadiff.SetActive(true);  // Active le canvas du menu principal
            }
            catch (MySqlException ex)
            {
                Debug.LogError("Erreur lors de l'enregistrement : " + ex.Message);
            }
        }
    }
    catch (MySqlException ex)
    {
        Debug.LogError("Erreur lors de la vérification du nom d'utilisateur : " + ex.Message);
    }
    finally
    {
        // Fermer la connexion et nettoyer
        checkCmd.Dispose();
        if (con != null && con.State == System.Data.ConnectionState.Open)
        {
            con.Close();
        }
    }
}


    public void login()
{
    ConnectBDD();

    // Query to check if the username and password match a record in the User table
    string loginCommand = "SELECT id, COUNT(*) FROM User WHERE username = @username AND mdp = @password";
    MySqlCommand loginCmd = new MySqlCommand(loginCommand, con);

    // Add parameters to avoid SQL injection
    loginCmd.Parameters.AddWithValue("@username", IfLogin.text);
    loginCmd.Parameters.AddWithValue("@password", IfPassword.text);

    try
    {
        // Execute the query to check if there is a matching user
        MySqlDataReader reader = loginCmd.ExecuteReader();

        if (reader.Read() && reader.GetInt32(1) > 0) // Vérifier si un utilisateur correspondant est trouvé
        {
            // Login successful
            userId = reader.GetInt32(0);  // Récupérer l'ID utilisateur
            Debug.Log("Login successful! Welcome " + IfLogin.text);
            user = IfLogin.text;
            mdp = IfPassword.text;
            canvalogin.SetActive(false);  // Désactive le canvas de login
            canvadiff.SetActive(true);  // Active le canvas du menu principal
        }
        else
        {
            // Invalid username or password
            Debug.Log("Invalid username or password.");
        }

        reader.Close();
    }
    catch (MySqlException ex)
    {
        Debug.LogError("Error during login: " + ex.Message);
    }
    finally
    {
        // Clean up and close the connection
        loginCmd.Dispose();
        if (con != null && con.State == System.Data.ConnectionState.Open)
        {
            con.Close();
        }
    }
}


     public void top()
{
    ConnectBDD();

    // Clear previous text
    txtTop.text = "";

    // SQL query to join the Game and User tables and get the top 7 scores with the player's username
    string query = "SELECT User.username, Game.score " +
                   "FROM Game " +
                   "JOIN User ON Game.user_id = User.id " +
                   "ORDER BY Game.score DESC " +
                   "LIMIT 7";
    
    MySqlCommand cmd = new MySqlCommand(query, con);

    try
    {
        MySqlDataReader reader = cmd.ExecuteReader();
        int rank = 1;  // Start the ranking from 1

        // Read through the results
        while (reader.Read())
        {
            string username = reader["username"].ToString();
            string score = reader["score"].ToString();

            // Display the rank, username, and score
            txtTop.text += rank.ToString() + ". " + username + " - Score: " + score + System.Environment.NewLine;
            rank++;
        }

        reader.Close();
    }
    catch (MySqlException ex)
    {
        Debug.LogError("Error fetching top scores: " + ex.Message);
    }
    finally
    {
        if (con != null && con.State == System.Data.ConnectionState.Open)
        {
            con.Close();
        }
    }
}



}
