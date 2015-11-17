# 1dv449-webagent

##Reflektion

###Finns det några etiska aspekter vid webbskrapning. Kan du hitta något rättsfall?  
Det finns absolut etiska aspekter vid webbskrapning. Eftersom en dator kan läsa en webbsida mycket snabbare än en männsika så bör man ha i åtanke att webbservern inte kan hantera alla förfrågningar som sker. Därför bör man exempelvis lägga in en time-out på sitt script för att inte överbelasta servern.   

Det finns också risk att personer som skrapar sidor på information publicerar den som sin egna. Även om datan du skrapar är fri att använda så finns det fortfarande saker som intelektuell egendom och copyright. 

Några rättsfall om webbskrapning:
* http://www.tomwbell.com/NetLaw/Ch06/eBay.html - Ebay vs Bidders Edge, resulterade inte i någon
* http://www.internetlibrary.com/pdf/efculturaltravel-zefer-1-cir.pdf - Flygbolag skrapar konkurrents priser för att sätta lägre, resulterade ej i någon dom. 
* https://www.techdirt.com/articles/20090605/2228205147.shtml - Facebook vinner rättsligt fall mot webbskrapning
* http://resources.distilnetworks.com/h/i/53822143-scraping-just-got-a-lot-more-dangerous/181642 - Associated Press och NY Times vinner fall mot Meltwater, en webbskrapare


###Finns det några riktlinjer för utvecklare att tänka på om man vill vara "en god skrapare" mot serverägarna?
Självklart finns det riktlinjer, även om alla inte följer dessa. Om man vill vara en "god skrapare". Först och främst så bör man kolla om det finns några "Terms of Use" på sidan och om dessa adresserar webb-skrapning. Ibland kan det stå här om man får skrapa och i såna fall vad man får skrapa. Man bör även kolla igenom robots.txt för att se om skrapning är tillåtet. Finns ingen av dessa två så kan man fråga ägaren. Att identifiera sig genom t.ex en mailadress i HTTP-headerns user-agent är god sed för skrapning.

###Begränsningar i din lösning- vad är generellt och vad är inte generellt i din kod?  
Det generella i koden är metoderna för att hämta data via en curl samt de metoder som gör om datat till DOMNodeList-typer och DOMXPath-typer. Dessa skulle kunna användas i andra webbskrapor. Resterande metoder är inte så generella utan knutna till sidorna skrapan skrapar.  

Skrapan skulle gå sönder om en till dag lades till i kalendern, så det är också en bergränsning. Skulle strukturen på någon sida ändras skulle skrapan också gå sönder. Detta känns som något nästan alla webbskrapor har gemensamt.

###Vad kan robots.txt spela för roll?
Robots.txt kan ge anvisningar om skrapning är tillåtet och vad som får/inte får skrapas. Att kolla igenom denna fil innan man börjar skrapa är ett bra och etiskt sätt att ta reda på detta utan att behöva komma muntligt överens med serverns ägare.

