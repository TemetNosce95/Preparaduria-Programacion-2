/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package servidortcp;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.*;
import java.util.ArrayList;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 *
 * @author temetnosce
 */
public class ServidorTCP {
    public static final int puerto = 30000;
    ServerSocket servidor = null;
    
    boolean detenerHiloRecepcion = false;
    ArrayList<Socket> socketsConectados;
    
    ArrayList<Thread> hilosLecturaSockets;
    boolean banderaIndividual = true;
    ArrayList<Boolean> banderasLecturaClientes;

    public ServidorTCP() {
        this.socketsConectados = new ArrayList();
        this.hilosLecturaSockets = new ArrayList();
        this.banderasLecturaClientes = new ArrayList();
    }
    
    public static void main(String[] args) throws IOException, InterruptedException {
        ServidorTCP stcp = new ServidorTCP();
        BufferedReader bufferSOUT = new BufferedReader(new InputStreamReader(System.in));
        
        if(stcp.conectar()) System.out.println("Conectado satisfactoriamente.");
        else{ System.err.println("Error al conectar"); System.exit(-1);}
        
        while(true){
            String s = bufferSOUT.readLine();
            if(s.compareTo("close") == 0) break;
        }
        
        System.out.println("saliendo del while");
        stcp.desconectar();
    }
    
    boolean conectar() throws IOException{
        try {
            servidor = new ServerSocket(puerto);
        } catch (IOException ex) {
            System.err.println("Error intentando abrir el servidor en el puerto seleccionado.");
            return false;
        }
        
        System.out.println("Hemos abierto el servidor en el puerto "+puerto);
        

        Thread hiloReceptor = new Thread(new Runnable() {

            @Override
            public void run() {
                while(!detenerHiloRecepcion){
                    Socket clienteEntrante = null;
                    try {
                        clienteEntrante = servidor.accept();
                    } catch (IOException ex) {
                        System.err.println("Error aceptando al cliente entrante.");
                        return;
                    }
                    
                    if(clienteEntrante != null){
                        System.out.println("Nuevo cliente aceptado.");
                        
                        synchronized(socketsConectados){
                            socketsConectados.add(clienteEntrante);
                        }
                        crearHiloLecturaIndividual(clienteEntrante);
                    }
                }
            }
        });
        
        hiloReceptor.start();
        return true;
    }
    
    void crearHiloLecturaIndividual(Socket cliente){
        if(cliente != null){
            try {
                HiloLecturaIndividual nuevoHilo = new HiloLecturaIndividual(cliente);
                nuevoHilo.start();
            } catch (IOException ex) {
                System.err.println("Error obteniendo el stream de lectura individual.");
            }
            
        }
    }
    
    synchronized void imprimirSOUT(String s){
        System.out.println(""+s);
    }
    
    void desconectar() throws IOException{
        detenerHiloRecepcion = true;
        
        banderaIndividual = false;
        
        for (Socket socket_a : socketsConectados) {
            socket_a.close();
        }
        
        servidor.close();
    }
    
    class HiloLecturaIndividual extends Thread{
        Socket cliente;
        InputStream streamEntrada;
        BufferedReader buffer;
        boolean activo;
        
        Object mutex = new Object();
        
        public HiloLecturaIndividual(Socket cliente) throws IOException {
            this.cliente = cliente;
            this.streamEntrada = cliente.getInputStream();
            this.buffer = new BufferedReader(new InputStreamReader(this.streamEntrada));
            this.activo = true;
        }

        @Override
        public void run() {
            while(activo){
                try {
                    if(this.streamEntrada.available() > 0){
                        System.out.println("[Desde "+ cliente.getInetAddress() + ":" + cliente.getPort()+"] = "+buffer.readLine());
                    }
                } catch (IOException ex) {
                    System.err.println("Error leyendo desde el socket "+this.cliente.getInetAddress()+":"+this.cliente.getPort()+".");
                }
            }
        }

        public Socket getCliente() {
            return cliente;
        }

        public void setCliente(Socket cliente) {
            this.cliente = cliente;
        }

        public InputStream getStreamEntrada() {
            return streamEntrada;
        }

        public void setStreamEntrada(InputStream streamEntrada) {
            this.streamEntrada = streamEntrada;
        }

        public boolean isActivo() {
            return activo;
        }

        public void setActivo(boolean activo) {
            synchronized(this.mutex){
                this.activo = activo;
            }
        }
        
        
        
    }
    
}
