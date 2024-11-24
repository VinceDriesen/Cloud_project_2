FROM docker.io/bitnami/dotnet-sdk:8.0.100

# Set working directory
WORKDIR /app

# Copy the project files
COPY *.csproj ./
COPY *.sln ./

# Restore dependencies
RUN dotnet restore

# Copy the rest of the application
COPY . ./

# Install dotnet-ef tool (voor specifieke versie)
RUN dotnet tool install --global dotnet-ef --version 8.0.0
ENV PATH="${PATH}:/root/.dotnet/tools"

# Build the application
RUN dotnet build -c Release -o out

# Expose port 5109 and run the application
EXPOSE 5109
CMD ["dotnet", "run", "--urls=http://0.0.0.0:5109"]
