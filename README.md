# USOS-user-homepage
Wtyczka USOS user homepage
Autor Klaudia Grygoruk

Instalacja
Wtyczka wymaga zainstalowania wtyczki Wordpress-Social-Login w wersji stworzonej przez Henryka Michalewskiego.
Przed instalacją należy podmienić plik wordpress/wp-content/plugins/wordpress-social-login/hybridauth/Hybrid/Providers/Usosweb.php na plik Usosweb.php załączony wraz z kodem wtyczki USOS-user-homepage.

Wtyczka zawiera bibliotekę fpdf, umożliwiającą tworzenie CV w formacie pdf.

Wtyczka pozwala na użycie shortcodów:
1. [uuh-schedule] - wyświetla plan użytkownika na dany tydzień
   Atrybuty: 
		description - default: true
		date - default: false
		start_hour - default: true
		end_hour - default: true
		visitors_info - default: ''
2. [uuh-grades] - wyświetla oceny użytkownika
   Atrybuty:
		description - default: true
		grade_description - default: true
		grade_value - default: true
		visitors_info - default: ''
3. [uuh-courses] - wyświetla aktualne przedmioty użytkownika
   Atrybuty:
		description - default: true
		visitors_info - default: ''
4. [uuh-employment] - wyświetla zatrudnienie i praktyki użytkownika
   Atrybuty:
		company_name - default: true
		link - default: true
		start_date - default: true
		end_date - default: true
		job - default: true
		visitors_info - default: ''
5. [uuh-projects] - wyświetla projekty użytkownika
   Atrybuty:
		name - default: true
		description - default: true
		job - default: true
		link - default: true
		visitors_info - default: ''

Atrybuty pozwalają wybrać, które informacje będą wyświetlane (wyświetlanie - true) oraz napisać informację, widoczną dla niezalogowanego użytkownika.

Jeśli użytkownik logował się przez USOS, plan(1), oceny(2) i przedmioty(3) wyświetlają odpowiednie dane pobrane przy logowaniu z USOSa.
Dla danych każdego shortcodu w panelu administracyjnym użytkownik może dodawać, wyszukiwać i usuwać dane (poza danymi z USOSa - ich użytkownik nie może modyfikować).
Z panelu administracyjnego można utworzyć CV zawierające informacje o zatrudnieniu i projektach użytkownika. Użytkownik może wybrać, czy zamieścić informację o danym zatrudnieniu/projekcie. CV generowane jest w pdf.

