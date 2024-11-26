# Gebruik een officiële Node.js image
FROM node:22.11.0

# Zet de werkdirectory in de container
WORKDIR /app

# Kopieer alleen package.json en package-lock.json voor dependencies
COPY package*.json ./

# Installeer de dependencies
RUN npm install

# Kopieer de broncode
COPY . /app

# Stel de poort in waar je app op draait (je kunt deze weglaten als je deze niet wilt expose)
EXPOSE 4000

# Zet geen automatisch startcommando, zodat je altijd zelf commando's kunt uitvoeren.
# CMD ["npm", "start"]  # Dit wordt niet gebruikt in de devcontainer.
