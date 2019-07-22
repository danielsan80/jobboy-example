# JobBoy Example

Questo progetto è un esempio di utilizzo di [JobBoy](https://github.com/danielsan80/jobboy).

Si tratta di un applicativo Symfony 4 con alcuni [piccoli adattamenti](./doc/notes.md)
(non rilevanti per utilizzare JobBoy).

In questa applicazione è stato aggiunto il JobBoyBundle ed è stato implementato un job di esempio `create_data_file`.

Il `create_data_file` crea un file .jsonl contenente n anagrafiche generate con
[Faker](https://github.com/fzaninotto/Faker).

Per farlo impiega diverse iterazioni sospendendo il lavoro e salvando lo stato di avanzamento nel Process di JobBoy.

Per definire un job in JobBoy è necessario sviluppare un set di ProcessHandler (che implementino l'interfaccia
`ProcessHandlerInterface`) che gestiscano in maniera esclusiva le varie casistiche nelle quali in job si può
travare.

E' possibile tralasciare alcune casistiche e delegarne la gestione a dei ProcessHandler generici
(con indice di priorità maggiore di 100). 

> La priority può trarre in inganno: il valore di default è 100, se si vuole registrare un ProcessHandler con
priorità più bassa sarà necessario impotare la priority ad un valore > 100 (110 ad esempio).  

Lo scopo di questo approccio è liberare periodicamente la memoria suddividendo il lavoro in più processi PHP eseguiti
sequenzialmente.

Inoltre la suddivisione del lavoro in più ProcessHandler permette di scalare sul numero di ProcessHandler piuttosto che
aumentare la complessità del programma che lo descrive.

I ProcessHandler implementati per il `create_data_file` sono i seguenti:

- **Initialize**: Eseguito una volta sola prepara la `working_dir` e vi inizializza un file temporaneo nel quale
verranno scritti i vari record generati salvandosi il path nel `ProcessStore` del `Process`. Inoltre inizializza a zero
il contatore dei record scritti. Porta il `Process` dallo stato `starting` a `runnning`.
- **Iterate**: E' il cuore del lavoro, verrà eseguito più volte ed ogni volta genererà un numero limitato di record 
salvandoli in chunk di alcuni record nel file temporaneo, aggiornando nel `ProcessStore` il contatore incrementale.
Raggiunto il numero di record da generare porterò il `Process` dallo stato `running` a `ending`
- **Finalize**: Eseguito una sola volta per spostare il file temporaneo ormai pronto nella posizione finale
richiesta nei parametri del `Process`. Porta il `Process` dallo stato `ending` a `completed`.
- **FreeHandled**: E' generico e registrato quindi con una priorità più bassa (110). Il suo scopo è portare nello
stato `failing` eventuali `Process` rimasti `handled` il che può verificarsi se viene interrotto il worker
durante un'iterazione.
- **Fail**: Quando il `Process` è in `failing` questo `ProcessHandler` fa il tear down del lavoro, cancellando il file
temporaneo, dopo di ché lo porterà in `failed`.   


I ProcessHandler devono essere registrati nel DIC di Symfony come servizi con il tag `jobboy.process_handler`.



## Resources
- [Notes](./doc/notes.md)
